<?php

namespace VisualComposer\Modules\Hub;

if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit;
}

use VisualComposer\Framework\Container;
use VisualComposer\Framework\Illuminate\Support\Module;
use VisualComposer\Helpers\Logger;
use VisualComposer\Helpers\Options;
use VisualComposer\Helpers\Request;
use VisualComposer\Helpers\Str;
use VisualComposer\Helpers\Traits\EventsFilters;

class JsonActionsController extends Container implements Module
{
    use EventsFilters;

    protected $optionsHelper;

    public function __construct(Options $optionsHelper)
    {
        $this->optionsHelper = $optionsHelper;
        $this->addFilter('vcv:ajax:hub:action:adminNonce', 'ajaxProcessAction');
        $this->addEvent('vcv:system:factory:reset', 'unsetOptions');
    }

    protected function ajaxProcessAction(
        $response,
        $payload,
        Request $requestHelper,
        Logger $loggerHelper,
        Str $strHelper
    ) {
        $isRequireUpdate = apply_filters('vcv:modules:hub:ajaxProcessAction:processRequire', false);
        if (empty($response)) {
            $response = [
                'status' => true,
            ];
        }
        $requestAction = $requestHelper->input('vcv-hub-action');

        if (!isset($requestAction['key'])) {
            sleep(5);

            return ['status' => true];
        }

        $newActionData = $this->optionsHelper->get('hubA:d:' . md5($requestAction['key']), false);
        $actionName = $newActionData['action'];

        $newActionVersion = $newActionData['version'];
        $previousActionVersion = $this->optionsHelper->get('hubAction:' . $actionName, '0');
        $elementTag = str_replace('element/', '', $actionName);

        // FIX: For cases when hubElements wasn't updated but hubAction already exists
        if ($strHelper->contains($actionName, 'element/')) {
            $elements = vchelper('HubElements')->getElements();

            if (!array_key_exists($elementTag, $elements)) {
                $previousActionVersion = '0.0.1'; // In case if element still not exists then try to download again
            }
        }

        $elementsToRegister = vchelper('DefaultElements')->all();
        if (in_array($elementTag, $elementsToRegister)) {
            $this->optionsHelper->delete('hubAction:' . $actionName);

            return $response;
        }
        if (!$isRequireUpdate && $newActionVersion === $previousActionVersion) {
            sleep(5); // Just to avoid collisions

            return $response;
        }

        $locked = $this->checkForLock($this->optionsHelper);
        if ($locked) {
            sleep(5); // Just to avoid collisions

            return ['status' => true];
        }
        if (!$newActionData) {
            sleep(5);
            $loggerHelper->log('The update action does not exists #10057');

            return $response;
        }
        if (vcIsBadResponse($response)) {
            return $response;
        }

        $response = $this->processAction(
            $response,
            $newActionData['action'],
            $newActionData['data'],
            $newActionData['version'],
            isset($newActionData['checksum']) ? $newActionData['checksum'] : ''
        );

        return $response;
    }

    protected function processAction(
        $response,
        $action,
        $data,
        $version,
        $checksum
    ) {
        $response = $this->triggerAction($response, $action, $data, $version, $checksum);
        if (is_array($response) && $response['status']) {
            $this->optionsHelper->set('hubAction:' . $action, $version);
            $this->optionsHelper->deleteTransient('vcv:hub:action:request');
        }

        return $response;
    }

    protected function triggerAction($response, $action, $data, $version, $checksum)
    {
        $this->clearAutoloadCache();
        $response = vcfilter(
            'vcv:hub:process:action:' . $action,
            $response,
            [
                'action' => $action,
                'data' => $data,
                'version' => $version,
                'checksum' => $checksum,
            ],
            true
        );
        if (!$response) {
            $this->optionsHelper->deleteTransient('vcv:hub:action:request');
        }

        return $response;
    }

    protected function clearAutoloadCache()
    {
        $this->optionsHelper->deleteTransient('addons:autoload:all');
        $this->optionsHelper->deleteTransient('elements:autoload:all');
    }

    protected function unsetOptions()
    {
        $this->optionsHelper->deleteTransient('vcv:activation:request');
        $this->optionsHelper->deleteTransient('vcv:hub:action:request');
        global $wpdb;
        $wpdb->query(
            $wpdb->prepare(
                'UPDATE ' . $wpdb->options
                . ' SET option_value="0.0.1" WHERE option_name LIKE %s AND NOT option_name = %s',
                VCV_PREFIX . 'hubAction:%',
                VCV_PREFIX . 'hubAction:updatePosts'
            )
        );
        $wpdb->query(
            $wpdb->prepare(
                'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE %s',
                VCV_PREFIX . 'hubA:d:%'
            )
        );
        // Remove before 1.13 keys
        $wpdb->query(
            $wpdb->prepare(
                'DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE %s',
                VCV_PREFIX . 'hubAction:download:%'
            )
        );
    }

    /**
     * @return bool
     */
    protected function checkForLock()
    {
        $currentRequest = $this->optionsHelper->getTransient('vcv:hub:action:request');
        if ($currentRequest) {
            // We have parallel request
            for ($tries = 0; $tries < 3; $tries++) {
                sleep(10);
                $newRequest = $this->optionsHelper->getTransient('vcv:hub:action:request');
                if (!$newRequest || $currentRequest !== $newRequest) {
                    // Process completed, we can return result
                    break;
                }
            }

            return true;
        }
        $this->optionsHelper->setTransient('vcv:hub:action:request', time(), 60);

        return false;
    }
}

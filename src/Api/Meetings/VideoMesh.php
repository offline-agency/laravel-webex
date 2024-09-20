<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\TrackingCodes as TrackingCodesEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\VideoMesh as VideoMeshEntity;

class VideoMesh extends AbstractApi
{
    public function listClusterAvailability(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/clusters/availability', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailClusterAvailability(
        string $clusterId,
        string $from,
        string $to
    ) {
        $response = $this->get('videoMesh/clusters/availability/'.$clusterId, [
            'from' => $from,
            'to' => $to
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listNodeAvailability(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/nodes/availability', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailNodeAvailability(
        string $nodeId,
        string $from,
        string $to
    ) {
        $response = $this->get('videoMesh/nodes/availability/'.$nodeId, [
            'from' => $from,
            'to' => $to
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listMediaHealthMonitoringToolResults(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/mediaHealthMonitor/', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function listMediaHealthMonitoringToolResultsV2(
        string $orgId,
        string $from,
        string $to,
        string $triggerType

    ) {
        $response = $this->get('videoMesh/testResults/mediaHealthMonitorTest/', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
            'triggerType' => $triggerType
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailMediaHealthMonitoringToolClusterResults(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/mediaHealthMonitor/clusters/', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function detailMediaHealthMonitoringToolClusterResultsV2(
        string $clusterId,
        string $from,
        string $to,
        string $triggerType
    ) {
        $response = $this->get('videoMesh/testResults/mediaHealthMonitorTest/clusters/', [
            'clusterId' => $clusterId,
            'from' => $from,
            'to' => $to,
            'triggerType' => $triggerType
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function detailMediaHealthMonitoringToolNodeResults(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/mediaHealthMonitor/nodes/', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function detailMediaHealthMonitoringToolNodeResultsV2(
        string $nodeId,
        string $from,
        string $to,
        string $triggerType
    ) {
        $response = $this->get('videoMesh/testResults/mediaHealthMonitorTest/nodes/', [
            'nodeId' => $nodeId,
            'from' => $from,
            'to' => $to,
            'triggerType' => $triggerType
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listOverflowToCloudDetails(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/cloudOverflow/', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function listClusterRedirectDetails(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/callRedirects/', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailClusterRedirectDetails(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/clusters/callRedirects/', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listClustersUtilization(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/utilization/', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailClusterUtilizationDetails(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/clusters/utilization/', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listReachabilityTestResults(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/reachabilityTest/', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function listReachabilityTestResultsV2(
        string $orgId,
        string $from,
        string $to,
        string $triggerType

    ) {
        $response = $this->get('videoMesh/testResults/reachabilityTest/', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
            'triggerType' => $triggerType
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailReachabilityTestResultsForCluster(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/reachabilityTest/clusters/', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function detailReachabilityTestResultsForClusterV2(
        string $clusterId,
        string $from,
        string $to,
        string $triggerType
    ) {
        $response = $this->get('videoMesh/testResults/reachabilityTest/clusters/', [
            'clusterId' => $clusterId,
            'from' => $from,
            'to' => $to,
            'triggerType' => $triggerType
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function detailReachabilityTestResultsForNode(
        string $from,
        string $to,
        string $nodeId
    ) {
        $response = $this->get('videoMesh/reachabilityTest/nodes/', [
            'from' => $from,
            'to' => $to,
            'nodeId' => $nodeId
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function detailReachabilityTestResultsForNodeV2(
        string $nodeId,
        string $from,
        string $to,
        string $triggerType
    ) {
        $response = $this->get('videoMesh/testResults/reachabilityTest/nodes/', [
            'nodeId' => $nodeId,
            'from' => $from,
            'to' => $to,
            'triggerType' => $triggerType
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listClusterDetails(
        string $orgId
    ) {
        $response = $this->get('videoMesh/clusters/', [
            'orgId' => $orgId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailCluster(
        string $clusterId
    ) {
        $response = $this->get('videoMesh/clusters/'.$clusterId, [
            'clusterId' => $clusterId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function triggerOnDemandTestForCluster(
        string $clusterId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'type', 'nodes',
        ]);

        $response = $this->put('videoMesh/triggerTest/clusters/'.$clusterId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new TrackingCodesEntity($response->data);
    }

    public function triggerOnDemandTestForNode(
        string $nodeId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'type',
        ]);

        $response = $this->put('videoMesh/triggerTest/nodes/'.$nodeId, $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new TrackingCodesEntity($response->data);
    }

    public function detailTriggeredTestStatus(
        string $commandId
    ) {
        $response = $this->get('videoMesh/testStatus', [
            'commandId' => $commandId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function detailTriggeredTestResults(
        string $commandId
    ) {
        $response = $this->get('videoMesh/testResults', [
            'commandId' => $commandId,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listNetworkTestResults(
        string $orgId,
        string $from,
        string $to,
        string $triggerType
    ) {
        $response = $this->get('videoMesh/testResults/networkTest', [
            'orgId' => $orgId,
            'from' => $from,
            'to' => $to,
            'triggerType' => $triggerType,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailNetworkTestResultsForCluster(
        string $clusterId,
        string $from,
        string $to,
        string $triggerType
    ) {
        $response = $this->get('videoMesh/testResults/networkTest/clusters', [
            'clusterId' => $clusterId,
            'from' => $from,
            'to' => $to,
            'triggerType' => $triggerType,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function detailNetworkTestResultsForNode(
        string $nodeId,
        string $from,
        string $to,
        string $triggerType
    ) {
        $response = $this->get('videoMesh/testResults/networkTest/nodes', [
            'nodeId' => $nodeId,
            'from' => $from,
            'to' => $to,
            'triggerType' => $triggerType,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listClusterClientTypeDistributionDetails(
        string $orgId,
        string $from,
        string $to,
        string $deviceType
    ) {
        $response = $this->get('videoMesh/clientTypeDistribution', [
            'orgId' => $orgId,
            'from' => $from,
            'to' => $to,
            'deviceType' => $deviceType,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailClusterClientTypeDistributionDetails(
        string $clusterId,
        string $from,
        string $to,
        string $deviceType
    ) {
        $response = $this->get('videoMesh/clientTypeDistribution/clusters', [
            'clusterId' => $clusterId,
            'from' => $from,
            'to' => $to,
            'triggerType' => $deviceType,
        ]);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    public function listEventThresholdConfiguration(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'orgId', 'clusterId', 'eventName', 'eventScope',
        ]);

        $response = $this->get('videoMesh/clientTypeDistribution', $additional_data);

        if (!$response->success) {
            return new Error($response->data);
        }

        $TrackingCodes = $response->data;

        return array_map(function ($TrackingCode) {
            return new VideoMeshEntity($TrackingCode);
        }, $TrackingCodes->items);
    }

    public function detailEventThresholdConfiguration(
        string $eventThresholdId
    ) {
        $response = $this->get('videoMesh/eventThresholds/'.$eventThresholdId, []);

        if (!$response->success) {
            return new Error($response->data);
        }

        return new VideoMeshEntity($response->data);
    }

    //TODO check function patch
    public function updateEventThresholdConfiguration(
        array $eventThresholdIds
    ) {
        $response = $this->put('videoMesh/eventThresholds', [
            'eventThresholdIds' => $eventThresholdIds,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }

    public function resetEventThresholdConfiguration(
        array $eventThresholdIds
    ) {
        $response = $this->post('videoMesh/eventThresholds/reset', [
            'eventThresholdIds' => $eventThresholdIds,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        return new MeetingsEntity($response->data);
    }
}

<?php

namespace Offlineagency\LaravelWebex\Api\Meetings;

use Offlineagency\LaravelWebex\Api\AbstractApi;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\TrackingCodes as TrackingCodesEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\VideoMesh as VideoMeshEntity;

class VideoMesh extends AbstractApi
{
    /**
     * @return VideoMeshEntity[]|Error
     */
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

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailClusterAvailability(
        string $clusterId,
        string $from,
        string $to
    ) {
        $response = $this->get('videoMesh/clusters/availability/'.$clusterId, [
            'from' => $from,
            'to' => $to,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
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

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailNodeAvailability(
        string $nodeId,
        string $from,
        string $to
    ) {
        $response = $this->get('videoMesh/nodes/availability/'.$nodeId, [
            'from' => $from,
            'to' => $to,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
    public function listMediaHealthMonitoringToolResults(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/testResults/mediaHealthMonitorTest', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
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
            'triggerType' => $triggerType,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailMediaHealthMonitoringToolClusterResults(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/testResults/mediaHealthMonitorTest/clusters', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
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
            'triggerType' => $triggerType,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailMediaHealthMonitoringToolNodeResults(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/testResults/mediaHealthMonitorTest/nodes', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
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
            'triggerType' => $triggerType,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
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

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
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

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailClusterRedirectDetails(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/clusters/callRedirects/', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
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

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailClusterUtilizationDetails(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/clusters/utilization/', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
    public function listReachabilityTestResults(
        string $from,
        string $to,
        string $orgId
    ) {
        $response = $this->get('videoMesh/testResults/reachabilityTest', [
            'from' => $from,
            'to' => $to,
            'orgId' => $orgId,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
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
            'triggerType' => $triggerType,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailReachabilityTestResultsForCluster(
        string $from,
        string $to,
        string $clusterId
    ) {
        $response = $this->get('videoMesh/testResults/reachabilityTest/clusters', [
            'from' => $from,
            'to' => $to,
            'clusterId' => $clusterId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
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
            'triggerType' => $triggerType,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailReachabilityTestResultsForNode(
        string $from,
        string $to,
        string $nodeId
    ) {
        $response = $this->get('videoMesh/testResults/reachabilityTest/nodes', [
            'from' => $from,
            'to' => $to,
            'nodeId' => $nodeId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
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
            'triggerType' => $triggerType,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
    public function listClusterDetails(
        string $orgId
    ) {
        $response = $this->get('videoMesh/clusters/', [
            'orgId' => $orgId,
        ]);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailCluster(
        string $clusterId
    ) {
        $response = $this->get('videoMesh/clusters/'.$clusterId, [
            'clusterId' => $clusterId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return TrackingCodesEntity|Error
     */
    public function triggerOnDemandTestForCluster(
        string $clusterId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'type', 'nodes',
        ]);

        $response = $this->post('videoMesh/triggerTest/clusters/'.$clusterId, $additional_data);

        return $this->entity($response, TrackingCodesEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return TrackingCodesEntity|Error
     */
    public function triggerOnDemandTestForNode(
        string $nodeId,
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'type',
        ]);

        $response = $this->post('videoMesh/triggerTest/nodes/'.$nodeId, $additional_data);

        return $this->entity($response, TrackingCodesEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailTriggeredTestStatus(
        string $commandId
    ) {
        $response = $this->get('videoMesh/testStatus', [
            'commandId' => $commandId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailTriggeredTestResults(
        string $commandId
    ) {
        $response = $this->get('videoMesh/testResults', [
            'commandId' => $commandId,
        ]);

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
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

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
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

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity|Error
     */
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

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @return VideoMeshEntity[]|Error
     */
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

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
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

        return $this->entity($response, VideoMeshEntity::class);
    }

    /**
     * @param  array<string, mixed>|null  $additional_data
     * @return VideoMeshEntity[]|Error
     */
    public function listEventThresholdConfiguration(
        ?array $additional_data = []
    ) {
        $additional_data = $this->data($additional_data, [
            'orgId', 'clusterId', 'eventName', 'eventScope',
        ]);

        $response = $this->get('videoMesh/clientTypeDistribution', $additional_data);

        if (! $response->success) {
            return new Error($response->data);
        }

        $items = $this->getItemsFromResponse($response);

        return array_map(function ($item) {
            return new VideoMeshEntity($item);
        }, $items);
    }

    /**
     * @return VideoMeshEntity|Error
     */
    public function detailEventThresholdConfiguration(
        string $eventThresholdId
    ) {
        $response = $this->get('videoMesh/eventThresholds/'.$eventThresholdId, []);

        return $this->entity($response, VideoMeshEntity::class);
    }

    // TODO check function patch
    /**
     * @param  list<string>  $eventThresholdIds
     * @return MeetingsEntity|Error
     */
    public function updateEventThresholdConfiguration(
        array $eventThresholdIds
    ) {
        $response = $this->patch('videoMesh/eventThresholds', [
            'eventThresholdIds' => $eventThresholdIds,
        ]);

        return $this->entity($response, MeetingsEntity::class);
    }

    /**
     * @param  list<string>  $eventThresholdIds
     * @return MeetingsEntity|Error
     */
    public function resetEventThresholdConfiguration(
        array $eventThresholdIds
    ) {
        $response = $this->post('videoMesh/eventThresholds/reset', [
            'eventThresholdIds' => $eventThresholdIds,
        ]);

        return $this->entity($response, MeetingsEntity::class);
    }
}

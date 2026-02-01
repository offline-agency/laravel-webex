<?php

use Illuminate\Support\Facades\Http;
use Offlineagency\LaravelWebex\Entities\Error;
use Offlineagency\LaravelWebex\Entities\Meetings\Meeting as MeetingsEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\TrackingCodes as TrackingCodesEntity;
use Offlineagency\LaravelWebex\Entities\Meetings\VideoMesh as VideoMeshEntity;
use Offlineagency\LaravelWebex\LaravelWebex;

describe('VideoMesh', function () {
    it('lists cluster availability', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/availability*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'c1', 'orgId' => 'o1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listClusterAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('returns error on list cluster availability failure', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/availability*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->listClusterAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('gets cluster availability detail', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/availability/c1*' => Http::response(json_encode((object) [
                'id' => 'c1',
                'availability' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailClusterAvailability('c1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists node availability', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/nodes/availability*' => Http::response(json_encode((object) [
                'items' => [
                    (object) ['id' => 'n1', 'clusterId' => 'c1'],
                ],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listNodeAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'c1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets node availability detail', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/nodes/availability/n1*' => Http::response(json_encode((object) [
                'id' => 'n1',
                'clusterId' => 'c1',
                'availability' => [],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailNodeAvailability('n1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('returns error on list node availability failure', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/nodes/availability*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->listNodeAvailability('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'c1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('lists media health monitoring tool results', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/mediaHealthMonitor/*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'm1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listMediaHealthMonitoringToolResults('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists media health monitoring tool results v2', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/mediaHealthMonitorTest/*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'm1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listMediaHealthMonitoringToolResultsV2('o1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail media health monitoring tool cluster results', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/mediaHealthMonitor/clusters/*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailMediaHealthMonitoringToolClusterResults('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'c1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail media health monitoring tool cluster results v2', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/mediaHealthMonitorTest/clusters/*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailMediaHealthMonitoringToolClusterResultsV2('c1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail media health monitoring tool node results', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/mediaHealthMonitor/nodes/*' => Http::response(json_encode((object) ['id' => 'n1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailMediaHealthMonitoringToolNodeResults('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail media health monitoring tool node results v2', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/mediaHealthMonitorTest/nodes/*' => Http::response(json_encode((object) ['id' => 'n1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailMediaHealthMonitoringToolNodeResultsV2('n1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists overflow to cloud details', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/cloudOverflow/*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'o1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listOverflowToCloudDetails('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists cluster redirect details', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/callRedirects/*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'r1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listClusterRedirectDetails('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail cluster redirect details', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/callRedirects/*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailClusterRedirectDetails('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'c1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists clusters utilization', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/utilization/*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'u1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listClustersUtilization('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail cluster utilization details', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/utilization/*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailClusterUtilizationDetails('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'c1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists reachability test results', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/reachabilityTest/*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'r1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listReachabilityTestResults('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists reachability test results v2', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/reachabilityTest/*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'r1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listReachabilityTestResultsV2('o1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail reachability test results for cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/reachabilityTest/clusters/*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailReachabilityTestResultsForCluster('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'c1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail reachability test results for cluster v2', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/reachabilityTest/clusters/*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailReachabilityTestResultsForClusterV2('c1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail reachability test results for node', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/reachabilityTest/nodes/*' => Http::response(json_encode((object) ['id' => 'n1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailReachabilityTestResultsForNode('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'n1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail reachability test results for node v2', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/reachabilityTest/nodes/*' => Http::response(json_encode((object) ['id' => 'n1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailReachabilityTestResultsForNodeV2('n1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists cluster details', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'c1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listClusterDetails('o1');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clusters/c1*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailCluster('c1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('triggers on demand test for cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/triggerTest/clusters/c1*' => Http::response(json_encode((object) ['commandId' => 'cmd1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->triggerOnDemandTestForCluster('c1');

        expect($result)->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('triggers on demand test for node', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/triggerTest/nodes/n1*' => Http::response(json_encode((object) ['commandId' => 'cmd1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->triggerOnDemandTestForNode('n1');

        expect($result)->toBeInstanceOf(TrackingCodesEntity::class);
    });

    it('gets detail triggered test status', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testStatus*' => Http::response(json_encode((object) ['commandId' => 'cmd1', 'status' => 'completed'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailTriggeredTestStatus('cmd1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail triggered test results', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults*' => Http::response(json_encode((object) ['commandId' => 'cmd1', 'results' => []])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailTriggeredTestResults('cmd1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists network test results', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/networkTest*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'nt1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listNetworkTestResults('o1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail network test results for cluster', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/networkTest/clusters*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailNetworkTestResultsForCluster('c1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail network test results for node', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/testResults/networkTest/nodes*' => Http::response(json_encode((object) ['id' => 'n1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailNetworkTestResultsForNode('n1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'scheduled');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists cluster client type distribution details', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clientTypeDistribution*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'ct1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listClusterClientTypeDistributionDetails('o1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'web');

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail cluster client type distribution details', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clientTypeDistribution/clusters*' => Http::response(json_encode((object) ['id' => 'c1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailClusterClientTypeDistributionDetails('c1', '2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'web');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('lists event threshold configuration', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/clientTypeDistribution*' => Http::response(json_encode((object) [
                'items' => [(object) ['id' => 'et1']],
            ])),
        ]);

        $laravel_webex = new LaravelWebex();
        $list = $laravel_webex->video_mesh()->listEventThresholdConfiguration();

        expect($list)->toHaveCount(1);
        expect($list[0])->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('gets detail event threshold configuration', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/eventThresholds/et1*' => Http::response(json_encode((object) ['id' => 'et1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->detailEventThresholdConfiguration('et1');

        expect($result)->toBeInstanceOf(VideoMeshEntity::class);
    });

    it('updates event threshold configuration', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/eventThresholds*' => Http::response(json_encode((object) ['id' => 'et1'])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->updateEventThresholdConfiguration(['et1']);

        expect($result)->toBeInstanceOf(MeetingsEntity::class);
    });

    it('resets event threshold configuration', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/eventThresholds/reset*' => Http::response(json_encode((object) ['id' => null])),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->resetEventThresholdConfiguration(['et1']);

        expect($result)->toBeInstanceOf(MeetingsEntity::class);
    });

    it('returns error on list media health monitoring tool failure', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/mediaHealthMonitor/*' => Http::response(json_encode((object) [
                'message' => 'Unauthorized',
                'errors' => [],
                'trackingId' => 't1',
            ]), 401),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->listMediaHealthMonitoringToolResults('2025-01-01T00:00:00Z', '2025-01-02T00:00:00Z', 'o1');

        expect($result)->toBeInstanceOf(Error::class);
    });

    it('returns error on trigger on demand test for cluster failure', function () {
        Http::fake([
            'https://webexapis.com/v1/videoMesh/triggerTest/clusters/c1*' => Http::response(json_encode((object) [
                'message' => 'Bad Request',
                'errors' => [],
                'trackingId' => 't1',
            ]), 400),
        ]);

        $laravel_webex = new LaravelWebex();
        $result = $laravel_webex->video_mesh()->triggerOnDemandTestForCluster('c1');

        expect($result)->toBeInstanceOf(Error::class);
    });
});

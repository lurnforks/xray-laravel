<?php

declare(strict_types=1);

namespace Napp\Xray\Submission;

use Pkerrigan\Xray\Segment;
use Pkerrigan\Xray\Submission\DaemonSegmentSubmitter as SubmissionDaemonSegmentSubmitter;
use Pkerrigan\Xray\Submission\SegmentSubmitter;

class DaemonSegmentSubmitter implements SegmentSubmitter
{
    /**
     * @var SubmissionDaemonSegmentSubmitter
     */
    private $submitter;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    public function __construct()
    {
        $lambdaSettings = explode(':', $_SERVER['AWS_XRAY_DAEMON_ADDRESS'] ?? '');
        var_dump($lambdaSettings);
        $this->host = !empty($lambdaSettings[0]) ? $lambdaSettings[0] : env('_AWS_XRAY_DAEMON_ADDRESS') ;
        $this->port = (int) (!empty($lambdaSettings[1])? $lambdaSettings[1] : env('_AWS_XRAY_DAEMON_PORT'));
    }

    /**
     * Get or create the Daemon submitter.
     *
     * @return SubmissionDaemonSegmentSubmitter
     */
    protected function submitter(): SubmissionDaemonSegmentSubmitter
    {
        if (is_null($this->submitter)) {
            $this->submitter = new SubmissionDaemonSegmentSubmitter(
                $this->host,
                $this->port
            );
        }

        return $this->submitter;
    }

    /**
     * @param Segment $segment
     * @return void
     */
    public function submitSegment(Segment $segment)
    {
        $this->submitter()->submitSegment($segment);
    }
}

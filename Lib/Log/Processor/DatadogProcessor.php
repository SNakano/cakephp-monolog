<?php
/**
 * DatadogProcessor append Datadog APM Trace ID and Span ID.
 *
 * This processor append the identifiers to all the log messages automatically.
 *
 * See: https://docs.datadoghq.com/tracing/advanced/connect_logs_and_traces/?tab=php
 */
class DatadogProcessor
{
    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record)
    {
		if (!class_exists("\DDTrace\GlobalTracer")) {
			return $record;
		}

		$span = \DDTrace\GlobalTracer::get()->getActiveSpan();
		if (null === $span) {
			return $record;
		}
		$record['message'] .= sprintf(
			' [dd.trace_id=%d dd.span_id=%d]',
			$span->getTraceId(),
			$span->getSpanId()
		);

		return $record;
    }
}

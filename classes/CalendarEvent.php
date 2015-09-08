<?php

class CalendarEvent {

	/**
	 * An ID for the event.
	 */
	public $id;

	/**
	 * A DateTime representing the starting time for this event.
	 */
	public $start;

	/**
	 * An integer representing seconds from $start that the event is going to end at.
	 */
	public $length;

	/**
	 * The place where the event takes place.
	 */
	public $location;

	/**
	 * A description of the event.
	 */
	public $summary;

	/**
	 * A description of the event.
	 */
	public $description;

	/**
	 * Converts a timestamp into a correct formatted date for iCalendar format.
	 */
	private function dateToCal($dateTime) {
		return $dateTime->format('Ymd\THis');
	}

	/** 
	 * Escapes a string of characters into an ICS compatible format.
	 */
	private function escapeString($string) {
	  	return preg_replace(array('/&nbsp;/', '/([:\,;])/'), array(' ', '\\\$1'), $string);
	}

	/**
	 * Returns valid ics for this event. Has to be placed inside a calendar file skeleton.
	 */
	public function getIcs() {
		return  "BEGIN:VEVENT\r\n" .
				"DTSTART;TZID=Europe/Stockholm:" . $this->dateToCal($this->start) . "\r\n" .
				"DTEND;TZID=Europe/Stockholm:" . $this->dateToCal($this->start->add(new DateInterval('PT' . $this->length . 'S'))) . "\r\n" .
				"DTSTAMP;TZID=Europe/Stockholm:" . $this->dateToCal(new DateTime) . "\r\n" .
				"UID:" . $this->escapeString($this->id) . "\r\n" .
				"CREATED;TZID=Europe/Stockholm:" . $this->dateToCal(new DateTime) . "\r\n" . 
				"LOCATION:" . $this->escapeString($this->location) . "\r\n" .
				"DESCRIPTION:" . $this->escapeString($this->description) . "\r\n" .
				"LAST-MODIFIED;TZID=Europe/Stockholm:" . $this->dateToCal(new DateTime) . "\r\n" .
				"SEQUENCE:0" . "\r\n" .
				"STATUS:CONFIRMED" . "\r\n" .
				"SUMMARY:" . $this->escapeString($this->summary) . "\r\n" .
				"END:VEVENT" . "\r\n";
	}
}
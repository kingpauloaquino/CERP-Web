<?php

class LookupStatus {
	const Pending = 19;
  const Incomplete = 22;
  const Complete = 21;
  const Open = 13;
  const Close = 14;
	const Issued = 24;
	const Released = 25;
	const Received = 26;
	
	function get_status_string($status) {
		switch($status) {
			case $this::Pending:
				return 'Pending';
			case $this::Incomplete:
				return 'Incomplete';
			case $this::Complete:
				return 'Complete';
			case $this::Open:
				return 'Open';
			case $this::Close:
				return 'Close';
			case $this::Issued:
				return 'Issued';
			case $this::Released:
				return 'Released';
			case $this::Received:
				return 'Received';
		}
	}
}

<?php 

	namespace AbraApi\Results;

	abstract class AbstractAbraApiResult {

		protected $abraResultHeaders;

		protected $httpCode;

		protected $content;

		protected function parseHeaders($headers) {
			foreach($headers as $headerKey => $headerValue) {
				$this->abraResultHeaders[$headerKey] = $headerValue;
			}
		}

		public function getHeader($key) {
			if(isset($this->abraResultHeaders[strtolower($key)])) return $this->abraResultHeaders[strtolower($key)];
			return null;
		}

		protected function setHttpCode($httpCode) {
			$this->httpCode = $httpCode;
			// logic for exceptions
			if($httpCode !== 200 && $httpCode !== 201 && $httpCode !== 204) {
				switch($httpCode) {
					case 0: {
						throw new NoResponseException("API is not responding, try to restart API´s Supervisor and Server.");
						break;
					}
					case 400: {
						$error = "Not-specified error occured (400) - propably some problem with Abra database consistency.";
						if(isset($this->content->description)) $error = $this->content->description;
						else if(isset($this->content->error)) $error = $this->content->error;
						throw new BadRequestException($error);
						break;
					}
					default: {
						$error = "Not-specified error (".$httpCode.") occured";
						if(isset($this->content->description)) $error = $this->content->description;
						else if(isset($this->content->error)) $error = $this->content->error;
						throw new \Exception($error);
					}
				}
			}
		}

		public function getHttpCode() {
			return $this->httpCode;
		}

		public function getHeaders() {
			return $this->abraResultHeaders;
		}

	}

	class NoResponseException extends \Exception {}
	class BadRequestException extends \Exception {}
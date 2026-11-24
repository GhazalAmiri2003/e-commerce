<?php
// Old database connection probe is deliberately unavailable over HTTP.
http_response_code(404);
exit;

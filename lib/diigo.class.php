<?

class diigo {

    var $http_code_text = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    );
    protected $diigo_base_url = 'https://secure.diigo.com/api/v2/';
    protected $api_key;
    protected $username;
    protected $password;
    var $header;
    var $http_code;
    var $content;
    var $curl_error;


    /*
     * Initiate the class
     */

    function diigo($api_key='', $username='', $password='') {
        $this->api_key = urlencode($api_key);
        $this->username = urlencode($username);
        $this->password = urlencode($password);
    }

// cURL

    function get_bookmarks($params) {
        $url = $this->diigo_base_url . 'bookmarks?key=' . $this->api_key.'&'.$params;
        $response = $this->get($url, '', true, $this->username . ':' . $this->password);
        if ($this->curl_error)
            return $this->curl_error;
        return $response;
    }
    function get_http_code(){
        return $this->http_code_text[$this->http_code];
    }

    /*
     * Runs a GET through cURL
     */

    private function get($url, $refer='', $https=false, $user_pass='') {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_REFERER, $refer);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);

        if ($https) {
            curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
            //curl_setopt($process, CURLOPT_SSL_VERIFYHOST, false);
        }
        if ($user_pass) {
            curl_setopt($process, CURLOPT_USERPWD, $user_pass);
        }
        $return = curl_exec($process);

        $this->http_code = curl_getinfo($process, CURLINFO_HTTP_CODE);
        $this->header = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
        $this->content = $return;

        if ($error = curl_error($process)) // Check for cURL errors
            $this->curl_error = $error;

        curl_close($process);
        return $return;
    }

// get

    /*
     * Runs a POST through cURL
     */

    private function post($url, $data, $refer, $https=false, $user_pass=false) {
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_REFERER, $refer);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POSTFIELDS, $data);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_POST, 1);

        if ($https) {
            curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
            //curl_setopt($process, CURLOPT_SSL_VERIFYHOST, false);
        }

        if ($user_pass) {
            curl_setopt($process, CURLOPT_USERPWD, $user_pass);
        }
        $return = curl_exec($process);

        $this->header = curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
        $this->content = $return;

        if ($error = curl_error($process)) // Check for cURL errors
            $this->curl_error = $error;

        curl_close($process);
        return true;
    }

// post

    function getContent() {
        return $this->content;
    }

    function getHeader() {
        return $this->header;
    }

    function getCurlError() {
        if ($error = $this->curl_error) {
            if (strstr($error, "Operation timed out")) {
                $return[0] = 2;
                $return[1] = "Connection timed out";
            }
            elseif (strstr($error, "Proxy CONNECT aborted due to timeout")) {
                $return[0] = 2;
                $return[1] = "Connection timed out";
            }
            elseif (strstr($error, "couldn't connect to host")) {
                $return[0] = 2;
                $return[1] = "Connection failed";
            }
            elseif (strstr($error, "Received HTTP code 503")) {
                $return[0] = 2;
                $return[1] = "Connection failed";
            }
            return $return;
        }
        else
            return false;
    }

// getCurlError            
}

// Class cURL
?>
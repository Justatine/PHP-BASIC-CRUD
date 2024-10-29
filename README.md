# PHP-BASIC-CRUD
-       header("Content-Type: application/json");
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

-       function getUrlParameter(name) {
            name = name.replace(/[\[\]]/g, "\\$&")
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(window.location.href)
            if (!results) return null
            if (!results[2]) return ''
            return decodeURIComponent(results[2].replace(/\+/g, " "))
        }

-       RewriteEngine On

        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule ^([^\.]+)$ $1.php [L,QSA]

-       function parse_multipart_formdata($data) {
                $fields = [];
                $boundary = substr($data, 0, strpos($data, "\r\n"));
                
                $parts = array_slice(explode($boundary, $data), 1);
                        foreach ($parts as $part) {
                        if ($part == "--\r\n") break;
                        $part = ltrim($part, "\r\n");
                        list($rawHeaders, $body) = explode("\r\n\r\n", $part, 2);
                        $rawHeaders = explode("\r\n", $rawHeaders);
                        $headers = [];
                        foreach ($rawHeaders as $header) {
                                list($name, $value) = explode(':', $header);
                                $headers[strtolower($name)] = ltrim($value, ' ');
                        }
                        if (isset($headers['content-disposition'])) {
                                $filename = null;
                                preg_match(
                                '/^(.+); *name="([^"]+)"(; *filename="([^"]+)")?/',
                                $headers['content-disposition'],
                                $matches
                                );
                                list(, $type, $name) = $matches;
                                isset($matches[4]) and $filename = $matches[4];
                        
                                switch ($name) {
                                case 'image':
                                $tmpFilePath = tempnam(sys_get_temp_dir(), 'uploaded_file');
                                $fields[$name] = [
                                'name' => $filename,
                                'tmp_name' => $tmpFilePath,
                                'type' => $headers['content-type']
                                ];
                                file_put_contents($tmpFilePath, $body);
                                break;
                                default:
                                $fields[$name] = substr($body, 0, strlen($body) - 2);
                                break;
                                }
                        }
                }
                return $fields;
        }
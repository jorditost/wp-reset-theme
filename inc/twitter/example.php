<?php
  require 'tmhOAuth/tmhOAuth.php';

  $tmhOAuth = new tmhOAuth(array(
    'consumer_key'    => 'vCXf51354Kw1yN2Q2bW5g',
    'consumer_secret' => 'bBI8ngzcL7Agfjs50cNdiqDi4UrPmlxsZZQHQAHVHY',
    'user_token'      => '111026634-sGLJImmx9Ht4e0OuuEglQ6yJnVQXrEBzLZdwhQaj',
    'user_secret'     => 'VFcsI6uHe4jBWkgvM3zqdSOziCr7KSUAv5CLE2ro',
  ));
  
  $code = $tmhOAuth->request('GET', 
                              $tmhOAuth->url('1.1/statuses/user_timeline'), 
                              array(
                                'screen_name' => 'thinkmoto',
                                'count' => '1'
                              ));
  
  $response = $tmhOAuth->response;
?>
<pre>
<?php
  print_r($response);
?>
</pre>
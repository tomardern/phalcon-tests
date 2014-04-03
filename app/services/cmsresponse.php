<?php 

//This adds abstract functionality to the current response
Class cmsresponse extends Phalcon\Http\Response {

        private $payload = array(
            "data" => array(),
            "meta" => array()
            //Payload could have pagination
        );

        

        function appendPayload($key,$data){
            $this->payload["data"][$key] = $data;
        }

        function appendMeta($key,$data){
            $this->payload["meta"][$key] = $data;]
        }


        function sendError($status = 501, $messages){

            if ($messages){
                $this->appendMeta("messages",$messages);                
            }

            $this->send($status);
        }

        function send($status = 200,$key = null,$data = null){
            //TODO Different HTTP Status CODes
            switch ($status) {
                case 200: $msg = "OK"; break;
                case 404: $msg = "NOT FOUND"; break;
            }

            //Set status code
            $this->payload["meta"]["status"] = $status;
            $this->setStatusCode($code,$msg);
            
            //Append data if has been passed
            if ($key && $data){                
                $this->appendPayload($key,$data);
            }
             $this->setJsonContent($this->payload);
            
            parent::send();
            return $this;
        }

    }
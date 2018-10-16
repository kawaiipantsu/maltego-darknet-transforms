<?php
/*
Just a quick and dirty lib to help with the parsing + creating of Maltego TDS transforms
If you want to update/rewrite feel free to email us your changes to maltego@paterva.com :)

This could do with some stricter checks on xml input and output ( making sure u dont add <lookibrokexml> ) etc.

-Andrew MacPherson ( andrew@paterva.com ) (2010/10/18)

*/

// KJB 2012-08-13 Add sanitization for UIMessages & Exceptions.
// KJB 2012-08-14 Add entity construction from local command line args.

//Maltego Entity class - handles the individual entitites
class MaltegoEntity
{
	var $value = "no value";
	var $weight = 100;
	var $displayInformation = "";
	var $additionalFields = array();
	var $iconURL = "";
	var $type = "maltego.Phrase";

	/* Constructor
		$type = entity type, eg maltego.Domain
		$value = value for the entity - eg 'helloworld.com'
	*/
	function MaltegoEntity($type,$value)
	{
		$this->type = $type;
		$this->value = $value;
	}

	/* setType
		$t = type, eg maltego.Domain
	*/
 	function setType($t)
 	{
 		$this->type = $t;
 	}
	
	/* setValue
		$v = value, eg helloworld.com
	*/
 	function setValue($v)
 	{
 		$this->value = utf8_encode($v);
 	}

	/* setWeight
		$w = weight, eg 100
	*/
 	function setWeight($w)
 	{
 		$this->weight = $w;
 	}

	/* setDisplayInformation
		$di = display info, what should be displayed within the client
	*/
 	function setDisplayInformation($di)
 	{
 		//$this->displayInformation = utf8_encode($di);
		$this->displayInformation = $di;
 	}

	/* addAdditionalFields
		$fieldName = the variable name of the field, so this is what you will use when reading from this entity later
		$displayName = what is displayed in the box
		$isKey = if the variable should be strict matched, set this to 'strict' to match ( so two entites with the same name+type but different variables would be seperate on the graph )
		$value = value of this field
	*/
 	function addAdditionalFields($fieldName,$displayName,$isKey="false",$value)
 	{
		/*
		$displayName = utf8_encode($displayName);
		$fieldName = utf8_encode($fieldName);
		$value = utf8_encode($value);
		*/
 		$this->additionalFields[] = array($fieldName,$displayName,$isKey,$value);
 	}

	/* setIconURL
		set the icon url ( what the entity looks like )
		$iu = full path to image, eg www.coolsite.com/funnypics/lol.jpg
	*/
 	function setIconURL($iu)
 	{
 		$this->iconURL = $iu;
 	}

	function sanitizeValue($val,$html = false)
	{
		//$val = mb_convert_encoding($val, "UTF-8","auto");
		if(strpos($val,"<![CDATA[") !== false)
		{
			//Already CDATA'd, leave it alone!
			return $val;
		}
		else
		{
			
			include_once("utf8.inc");
			//once to remove &amps;
			//$val = html_entity_decode($val, ENT_QUOTES, 'UTF-8');
			//once to decode numeric ref &#0955;
			//$val = html_entity_decode($val, ENT_QUOTES, 'UTF-8');
			
			//Replace Numeric Entities
			$val = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $val);
			$val = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $val);
			
			//$val = mb_convert_encoding($, 'UTF-8', 'HTML-ENTITIES');
			
			$numeric = utf8ToUnicode($val);
			$response = "";
			if($numeric <> false)
			{
				$response = "";
				foreach($numeric as $n)
				{
					$badChars = array(38,60,62);
					if ($html == true)
					{
						$badChars = array(38);
					}
					
					if($n > 126 || in_array($n,$badChars))
					{
						$response .=  "&#" . $n . ";";
					}
					else
					{
						$response .= chr($n);
					}

				}
				//$response .= "MMM";
			}
			else
			{
				$response = $val;
			}
			//$response .= implode("-",$numeric);
			//$val = mb_convert_encoding($val, "UTF-8", "auto");
			
			//$val = html_entity_decode($val);
			//$val = utf8_encode($val);
			//return "<![CDATA[" . $response . "]]>";
			return "" . $response . "";
		}
	}
	
	
	/* returnEntity
		function used by the MaltegoTransform to return each entities output
	*/
 	function returnEntity()
 	{
 		$output  = "<Entity Type='" . $this->type . "'>";
 		$output .= "<Value>" . $this->sanitizeValue($this->value) . "</Value>";
 		$output .= "<Weight>" . $this->weight . "</Weight>";
 		if ($this->displayInformation <> "")
 		{
 			$output .= "<DisplayInformation><Label Name=\"Display Information\" Type=\"text/html\"><![CDATA[" . $this->sanitizeValue($this->displayInformation,true) . "]]></Label></DisplayInformation>";
 		}

 		if (count($this->additionalFields) > 0)
 		{
 			$output .= "<AdditionalFields>";
 			foreach($this->additionalFields as $af)
 			{
				//$af[0] = $this->sanitizeValue($af[0]); // field name
				//$af[1] = $this->sanitizeValue($af[1]); // display name
				//$af[2] = $this->sanitizeValue($af[2]); // matchingRule
				$af[3] = $this->sanitizeValue($af[3]); //value
				
 				if ($af[2] <> "strict")
 				{
 					$output .= "<Field Name=\"" . $af[0] . "\" DisplayName=\"" . $af[1] . "\" >" . $af[3] . "</Field>";
 				}
 				else
 				{
 					$output .= "<Field MatchingRule=\"" . $af[2] . "\" Name=\"" . $af[0] . "\" DisplayName=\"" . $af[1] . "\" >" . $af[3] . "</Field>";
 				}
 			}
 			$output .= "</AdditionalFields>";
 		}

 		if ($this->iconURL <> "")
 		{
 			$output .="<IconURL>" . $this->iconURL . "</IconURL>";
 		}
 		$output .= "</Entity>\n";
 		return $output;
 	}
}

//MaltegoTransformInput - only does entity input ( from TDS ) at the moment
class MaltegoTransformInput
{
	
	var $type = "maltego.Phrase";
	var $value = "no value";
	var $weight = "100";
	var $additionalFields = array();
	var $transformFields = array();
	var $slider = "12";
	
	//empty constructor :(
	function MaltegoTransformInput()
	{
		
	}
	
	/*getEntity
		Parses entity input
	*/
	function getEntity()
	{
	    global $argc, $argv;
	    if ($argv && $argv[1]) {
	        return $this->populateEntityFromLocal();
	    } else {
	        return $this->populateEntityFromXML();
	    }
	}
	
	/* Populate entity from Local (command line args)
	 * 
	 */
	private function populateEntityFromLocal()
	{
	    global $argc, $argv;
	    
	    // Leave at default Maltego.Phrase type.
	    $this->value = (string)$argv[1];
	    if ($argv[2]) {
            parse_str(implode('&',explode("#", $argv[2])), $aFs_input);
            $aFs = array();
            foreach($aFs_input as $key => $val) {
                $aFs[(string)$key] = (string)$val;
            }
            $this->additionalFields = $aFs;
	    }
	    return true;
	}
	
	
	/* Populate entity from XML input (e.g. via TDS)
	 * 
	 */
	private function populateEntityFromXML() 
	{
		$xml = "No XML";
		$xmlPost = file_get_contents('php://input');
		if($xmlPost)
		{
			$xml = $xmlPost;
		}
		try
		{
			$entXML = @new SimpleXMLElement($xml);
			$entities = array();
			if (!empty($entXML))
			{
				
				foreach($entXML->MaltegoTransformRequestMessage->Entities->Entity as $e)
				{					
					$this->type = (string)$e["Type"];
					$this->value = (string)$e->Value;
					$this->weight = (string)$e->Weight;

					$aFs = array();
					$tFs = array();
					if($e->AdditionalFields)
					{
						
						foreach($e->AdditionalFields->Field as $aF)
						{
							$aFs[(string)$aF["Name"]] = (string)$aF;
						}
					}
					$this->additionalFields = $aFs;
					
				}
				$tFs = array();
				if($entXML->MaltegoTransformRequestMessage->TransformFields->Field)
				foreach($entXML->MaltegoTransformRequestMessage->TransformFields->Field as $tF)
				{
					$tFs[(string)$tF["Name"]] = (string)$tF;
				}
				$this->transformFields = $tFs;
				$this->slider = (string)$entXML->MaltegoTransformRequestMessage->Limits["HardLimit"];
				return true;
			}
			
		}
		catch (Exception $e)
		{
			return false;
		}
		return false;
		
	}
}


//Maltego Transform Response class - handles the transform response input and output xml as well as the entities
class MaltegoTransformResponse
{
	var $entities = array();
	var $exceptions = array();
	var $UIMessages = array();

	//constructor
 	function MaltegoTransform()
 	{

 	}

	/* addEntity
		basic add entity function, will create a basic entity with just type and value specified, 
		if you wish to extend this entity simply catch the return and use the functions above, so:
		
		//Basic:
		MaltegoTransform->addEntity('domain','helloworld.com');
		
		//Advanced:
		myEntity = MaltegoTransform->addEntity('domain','helloworld.com');
		myEntity->setWeight(50);
		
	*/
 	function addEntity($type,$value)
 	{
 		$e = new MaltegoEntity($type,$value);
 		$this->addEntitytoMessage($e);
 		return $this->entities[count($this->entities) - 1];

 	}

	/*	addEntitytoMessage
		internal function used to simply add an entity to this class.
	*/
 	function addEntitytoMessage($e)
 	{
 		$this->entities[] = $e;
 	}

	/* addUIMessage
		add a UIMessage to the response ( something displayed within the GUI in the output window )
		
		$message = message to be displayed
		$type = message type in [ 'FatalError', 'PartialError' , 'Inform', 'Debug']
	*/
	
 	function addUIMessage($message,$type = "PartialError")
 	{
 		$this->UIMessages[] = array($type,$message);
 	}
	
	/* addException
		add an exception to this class
		
		$exception - message to be sent back, please note exceptions cannot be sent back with entities!
	*/
 	function addException($exception)
 	{
 		$this->exceptions[] = $exception;
 	}


 	/* throwExceptions
		throw the exceptions, this will be done without returning any entities, useful for error situations
	*/
 	function throwExceptions()
 	{
 		$output = "<MaltegoMessage>\n";
 		$output .= "<MaltegoTransformExceptionMessage>\n";
 		$output .= "<Exceptions>\n";
 		foreach($this->exceptions as $x)
 		{
 			$output .="<Exception>" . MaltegoEntity::sanitizeValue($x) . "</Exception>\n";
 		}
 		$output .= "</Exceptions>\n";
 		$output .= "</MaltegoTransformExceptionMessage>\n";
 		$output .= "</MaltegoMessage>\n";
 		echo $output;
 	}

	/* returnOutput
		return the transform including all entities and UI Messages
	*/
 	function returnOutput()
 	{
		echo '<?xml version="1.0" encoding="UTF-8"?>';
 		$output = "<MaltegoMessage>\n";
 		$output .= "<MaltegoTransformResponseMessage>\n";
 		$output .="<Entities>\n";
 		foreach($this->entities as $e)
 		{
 			$output .= $e->returnEntity();
 		}
 		$output .="</Entities>\n";

 		$output .="<UIMessages>\n";
 		foreach($this->UIMessages as $ui)
 		{
 			$output .= "<UIMessage MessageType='" . $ui[0] . "'>" . MaltegoEntity::sanitizeValue($ui[1]) . "</UIMessage>\n";
 		}
 		$output .="</UIMessages>\n";

 		$output .= "</MaltegoTransformResponseMessage>\n";
 		$output .= "</MaltegoMessage>\n";
 		echo $output;
 	}

 }
 ?>

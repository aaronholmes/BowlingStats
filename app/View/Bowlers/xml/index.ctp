<?php
	try {
	    $xml = Xml::build($bowlerData);
    	echo $xml->saveXML();
	} catch (XmlException $e) {

	}
?>
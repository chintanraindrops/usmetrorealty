<!DOCTYPE html>
<html>
<head>
	<title>RETS DEMO</title>
</head>
<body>
	<h3>Arizona:</h3>

	<?php
		// https://github.com/troydavisson/PHRETS --- Reference link
		$rets_login_url = 'http://retsgw.flexmls.com:80/rets2_1/Login';
		$rets_username = 'az.rets.usmr01b';
		$rets_password = 'puces-uronic23';

		// set your timezone
		date_default_timezone_set('America/New_York');

		// pull in the packages managed by Composer
		require_once("vendor/autoload.php");

		// setup your configuration
		$config = new \PHRETS\Configuration;
		$config->setLoginUrl($rets_login_url)
		        ->setUsername($rets_username)
		        ->setPassword($rets_password)
		        ->setRetsVersion('1.7.2');

		// get a session ready using the configuration
		$rets = new \PHRETS\Session($config);

		// make the first request
		$connect = $rets->Login();

		$system = $rets->GetSystemMetadata();

		echo "Server Name: " . $system->getSystemDescription()."<br/><br/>";

		$results = $rets->Search("Property", "A", "(LIST_87=2010-04-01T00:00:00+)", array('Limit' => 2) );

		$properties = array();
		$keys = $results->getHeaders();
		$labels = array();
		$labels[] = "Assessor's Book #";
		$labels[] = "Assessor's Map #";
		$labels[] = "Approx Lot SqFt";
		$labels[] = "Contact Info: List Agent Cell Phn2";
		$labels[] = "Assessor's Parcel #";
		$labels[] = "Association & Fees: HOA Telephone";
		$labels[] = "Cooling";
		$labels[] = "VOWAddressDisplay";
		$labels[] = "Items Updated: Bath(s) Partial/Full";
		$labels[] = "Association & Fees: Cap Imprv/Impact Fee";
		$labels[] = "ListingOfficePhone";
		$labels[] = "Association & Fees: HOA 3 Telephone";
		$labels[] = "Separate Workshop Length";
		$labels[] = "Guest House SqFt";
		$labels[] = "Other Compensation";
		$labels[] = "CoListingMemberShortId";
		$labels[] = "Picture Count";
		$labels[] = "Picture Timestamp";
		$labels[] = "Source of SqFt";
		$labels[] = "Bedroom 4 Width";
		$labels[] = "Auction";
		$labels[] = "Tax Municipality";
		$labels[] = "Horses";
		$labels[] = "Ownership";
		$labels[] = "Association & Fees: HOA 2 Transfer Fee";
		$labels[] = "Items Updated: Wiring Partial/Full";
		$labels[] = "Fencing";
		$labels[] = "Const - Finish";
		$labels[] = "Timestamp";
		$labels[] = "Assessor Parcel Ltr";
		$labels[] = "Out of Area Schl Dst";
		$labels[] = "Cross Street";
		$labels[] = "CoListingMemberName";
		$labels[] = "Entry Timestamp";
		$labels[] = "Subdivision";
		$labels[] = "Builder Name";
		$labels[] = "Marketing Name";
		$labels[] = "Directions";
		$labels[] = "Legal Info: Cnty Rcrd Bk & Pg #";
		$labels[] = "Assessor Number";
		$labels[] = "Items Updated: Rm Adtn Partial/Full";
		$labels[] = "Spa";
		$labels[] = "Exercise/Sauna Width";
		$labels[] = "Energy/Green Feature";
		$labels[] = "Bedroom 5 Width";
		$labels[] = "Items Updated: Floor Partial/Full";
		$labels[] = "Legal Info: Township";
		$labels[] = "VOWConsumerComment";
		$labels[] = "Office Width";
		$labels[] = "Bedroom 3 Length";
		$labels[] = "Kitchen Length";
		$labels[] = "Dwelling Styles";
		$labels[] = "Bedroom 5 Length";
		$labels[] = "Public Remarks";
		$labels[] = "Master Bedroom Length";
		$labels[] = "Tax Year";
		$labels[] = "Pool";
		$labels[] = "Taxes";
		$labels[] = "Association & Fees: HOA 3 Fee";
		$labels[] = "Association & Fees: HOA Transfer Fee";
		$labels[] = "Source Apx Lot SqFt";
		$labels[] = "Parking Spaces: Carport Spaces";
		$labels[] = "Other Rooms";
		$labels[] = "Kitchen Width";
		$labels[] = "Association & Fees: HOA 2 Paid (Freq)";
		$labels[] = "Association & Fees: Rec Center Fee 2 Y/N";
		$labels[] = "Parking Spaces: Total Covered Spaces";
		$labels[] = "Property Description";
		$labels[] = "Exterior Stories";
		$labels[] = "# Bedrooms";
		$labels[] = "Association Fee Incl";
		$labels[] = "# Bathrooms";
		$labels[] = "# of Interior Levels";
		$labels[] = "Items Updated: Pool Yr Updated";
		$labels[] = "Association & Fees: Rec Center Fee";
		$labels[] = "Other Length";
		$labels[] = "Community Features";
		$labels[] = "Assoc Rules/Info";
		$labels[] = "Energy/Green Feature: HERS Rating Y/N";
		$labels[] = "Association & Fees: HOA Fee";
		$labels[] = "Plumbing";
		$labels[] = "Landscaping";
		$labels[] = "Items Updated: Plmbg Partial/Full";
		$labels[] = "Library Width";
		$labels[] = "Breakfast Room Width";
		$labels[] = "Association & Fees: HOA 2 Fee";
		$labels[] = "Items Updated: Roof Yr Updated";
		$labels[] = "ListingMemberName";
		$labels[] = "VOWEntireListingDisplay";
		$labels[] = "Bedroom 3 Width";
		$labels[] = "Bedroom 2 Width";
		$labels[] = "Green/Energy Cert";
		$labels[] = "Library Length";
		$labels[] = "Energy/Green Feature: HERS Rating";
		$labels[] = "Energy/Green Feature: HERS Cert Date";
		$labels[] = "Miscellaneous";
		$labels[] = "Items Updated: Roof Partial/Full";
		$labels[] = "Building Style";
		$labels[] = "Loft Length";
		$labels[] = "Add'l Property Use";
		$labels[] = "Great Room Width";
		$labels[] = "Association & Fees: HOA 2 Telephone";
		$labels[] = "Windows";
		$labels[] = "Items Updated: Plmbg Yr Updated";
		$labels[] = "Association & Fees: Rec Cent 2 Pd (Freq)";
		$labels[] = "Solar Panels: Ownership";
		$labels[] = "Roofing";
		$labels[] = "Separate Den/Office: Sep Den/Office Y/N";
		$labels[] = "Family Room Width";
		$labels[] = "Association & Fees: Com Facilities Distr";
		$labels[] = "Accessibility Feat.";
		$labels[] = "Heating";
		$labels[] = "Dwelling Type";
		$labels[] = "Association & Fees: HOA Name";
		$labels[] = "Association & Fees: Cap Impv/Impt Fee 2";
		$labels[] = "Association & Fees: HOA 3 Paid (Freq)";
		$labels[] = "Internal Listing ID";
		$labels[] = "Property Type";
		$labels[] = "Property Group ID";
		$labels[] = "Agent ID";
		$labels[] = "Items Updated: Wiring Yr Updated";
		$labels[] = "Family Room Length";
		$labels[] = "Kitchen Features";
		$labels[] = "Association & Fees: PAD Paid (Freq)";
		$labels[] = "Den Length";
		$labels[] = "Items Updated: Kitchen Yr Updated";
		$labels[] = "AZ Room/Lanai Length";
		$labels[] = "Exterior Features";
		$labels[] = "Contact Info: Office Fax Number";
		$labels[] = "Horse Features";
		$labels[] = "Cooling: HVAC SEER Rating";
		$labels[] = "Association & Fees: Land Lease Fee Y/N";
		$labels[] = "Separate Workshop Width";
		$labels[] = "Association & Fees: HOA 3 Y/N";
		$labels[] = "Association & Fees: Land Lease Fee";
		$labels[] = "Association & Fees: Land Lease Pd (Freq)";
		$labels[] = "New Financing";
		$labels[] = "Items Updated: Rm Adtn Yr Updated";
		$labels[] = "Association & Fees: Rec Center Pd (Freq)";
		$labels[] = "ListingMemberShortId";
		$labels[] = "Unit Style";
		$labels[] = "Items Updated: Ht/Cool Partial/Full";
		$labels[] = "UCB or CCBS";
		$labels[] = "Living Room Width";
		$labels[] = "Association & Fees: PAD Fee";
		$labels[] = "Status";
		$labels[] = "Laundry";
		$labels[] = "Bedroom 4 Length";
		$labels[] = "Architecture";
		$labels[] = "Green/Engy Cert Year: Green/Engy Cert Year";
		$labels[] = "Association & Fees: HOA Y/N";
		$labels[] = "Association & Fees: Rec Center Fee 2";
		$labels[] = "Contact Info: List Agent Pager";
		$labels[] = "Association & Fees: Cap Impv/ImptFee2Y/N";
		$labels[] = "Great Room Length";
		$labels[] = "Living Room Length";
		$labels[] = "Parking Spaces: Slab Parking Spaces";
		$labels[] = "Basement: Basement Y/N";
		$labels[] = "Association & Fees: HOA 2 Y/N";
		$labels[] = "Other Width";
		$labels[] = "Legal Info: Range";
		$labels[] = "Master Bedroom";
		$labels[] = "Status Update";
		$labels[] = "Construction Status";
		$labels[] = "Utilities";
		$labels[] = "Bonus/Game Room Length";
		$labels[] = "Master Bedroom Width";
		$labels[] = "UnBranded Virtual Tour";
		$labels[] = "VOWAutomatedValuationDisplay";
		$labels[] = "Association & Fees: HOA 3 Name";
		$labels[] = "Items Updated: Floor Yr Updated";
		$labels[] = "Approx SQFT";
		$labels[] = "Mfg Home Features";
		$labels[] = "Geo Lon";
		$labels[] = "Geo Lat";
		$labels[] = "Zip Code";
		$labels[] = "Special Listing Cond";
		$labels[] = "Media Room Length";
		$labels[] = "Services";
		$labels[] = "Association & Fees: Cap Impv/Impt Fee$/%";
		$labels[] = "Construction";
		$labels[] = "Media Room Width";
		$labels[] = "Flooring";
		$labels[] = "Items Updated: Kitchen Partial/Full";
		$labels[] = "Solar Panels: kW";
		$labels[] = "Items Updated: Ht/Cool Yr Updated";
		$labels[] = "Loft Width";
		$labels[] = "Year Built";
		$labels[] = "Association & Fees: Cap Impv/Impt FeeY/N";
		$labels[] = "Bedrooms Plus";
		$labels[] = "Association & Fees: HOA 2 Name";
		$labels[] = "Features";
		$labels[] = "Subagents";
		$labels[] = "Buyer/Broker";
		$labels[] = "Buyer Broker $/%";
		$labels[] = "Technology";
		$labels[] = "Apx Lot Size Range";
		$labels[] = "Architect: Architect";
		$labels[] = "Bonus/Game Room Width";
		$labels[] = "Legal Info: Lot Number";
		$labels[] = "Parking Features";
		$labels[] = "Association & Fees: Ttl Mthly Fee Equiv";
		$labels[] = "Office Length";
		$labels[] = "Items Updated: Pool Partial/Full";
		$labels[] = "Association & Fees: Rec Center Fee Y/N";
		$labels[] = "Variable Commission";
		$labels[] = "Dining Area";
		$labels[] = "Master Bathroom";
		$labels[] = "List Price";
		$labels[] = "Price/SqFt";
		$labels[] = "Additional Bedroom";
		$labels[] = "Den Width";
		$labels[] = "Bedroom 2 Length";
		$labels[] = "Map Code/Grid";
		$labels[] = "Sewer";
		$labels[] = "Association & Fees: Cap Impv/ImptFee2$/%";
		$labels[] = "Jr. High School";
		$labels[] = "Elementary School";
		$labels[] = "Pool - Private";
		$labels[] = "Water";
		$labels[] = "Listing ID";
		$labels[] = "Type";
		$labels[] = "Dining Room Length";
		$labels[] = "AZ Room/Lanai Width";
		$labels[] = "Office ID";
		$labels[] = "Basement Description";
		$labels[] = "Planned Comm Name";
		$labels[] = "House Number";
		$labels[] = "Contact Info: List Agent Cell Phn";
		$labels[] = "Association & Fees: HOA Paid (Freq)";
		$labels[] = "Compass";
		$labels[] = "Breakfast Room Length";
		$labels[] = "High School";
		$labels[] = "Bldg Number";
		$labels[] = "Association & Fees: PAD Fee Y/N";
		$labels[] = "Unit #";
		$labels[] = "Street Name";
		$labels[] = "St Suffix";
		$labels[] = "St Dir Sfx";
		$labels[] = "Exercise/Sauna Length";
		$labels[] = "ListingOfficeShortId";
		$labels[] = "Fireplace";
		$labels[] = "City/Town Code";
		$labels[] = "Sub Agent $/%";
		$labels[] = "Items Updated: Bath(s) Yr Updated";
		$labels[] = "ListingOfficeName";
		$labels[] = "Dining Room Width";
		$labels[] = "Legal Info: Section";
		$labels[] = "Comp to Buyer Broker";
		$labels[] = "Comp to Subagent";
		$labels[] = "Week Avail Timeshare";
		$labels[] = "Solar Panels: Grid";
		$labels[] = "Association & Fees: HOA 3 Transfer Fee";
		$labels[] = "Zip4";
		$labels[] = "Parking Spaces: Garage Spaces";
		$labels[] = "Approx SqFt Range";
		$labels[] = "State/Province";
		$labels[] = "Model";
		$labels[] = "High School Dist #";
		$labels[] = "County Code";
		$labels[] = "Country";
		$labels[] = "Elem School Dist #";
		$count = 0;

		foreach ($results as $record) {
			echo "<table border='1'>";
			for ($i=0; $i < count($keys); $i++) { 
				// $properties[$count][$i]['key'] = $keys[$i];
				// $properties[$count][$i]['value'] = $record[$keys[$i]];

				echo "<tr><th align='left'>".$keys[$i]."</th><td>".$labels[$i]."</td><td>".$record[$keys[$i]]."</td></tr>";
			}
			$count++;
			echo "</table><br/><br/>";

		    // echo $record['GF20080207210325432062000000'] . "\n";
		    // // is the same as:
		    // echo $record->get('ROOM_OT_room_length') . "\n";
		}

		// echo "<pre>"; print_r($properties); exit;

		// $photos = $rets->GetObject('Property', 'Photo', '20160416190535185409000000');
		// 		foreach ($photos as $photo) {
  //       $listing = $photo->Content-ID;
  //       $number = $photo->Object-ID;

  //       if ($photo['Success'] == true) {
  //               file_put_contents("image-{$listing}-{$number}.jpg", $photo['Data']);
  //       }
  //       else {
  //               echo "({$listing}-{$number}): {$photo['ReplyCode']} = {$photo['ReplyText']}\n";
  //       }

		$photos = $rets->GetObject("Property", "640x480", "20160416190535185409000000", "*", 1);
foreach ($photos as $photo) {
	$photo = (array)$photo;
	echo "<pre>"; print_r($photo); 

        // $listing = $photo->content_id;
        // $number = $photo->object_id;

		
	$data = array();
		foreach ($photo as $key => $value) {
			$data[] = $value;
		}

              $listing = $data[1];
        $number = $data[2];

        echo "<pre>";print_r($listing);
        if ($data[4]) {
                file_put_contents("image-{$listing}-{$number}.jpg", $data[4]);
        }
        else {
                echo "here in else";
        }
}




	?>
</body>
</html>
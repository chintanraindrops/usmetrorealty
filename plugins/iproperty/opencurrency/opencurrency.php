<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined('_JEXEC' ) or die( 'Restricted access');
jimport('joomla.plugin.plugin');

class plgIpropertyOpencurrency extends JPlugin
{
	public function __construct(&$subject, $config)  
    {
		parent::__construct($subject, $config);
        $this->loadLanguage();
    }

	public function onAfterRenderMap($property, $settings)
	{
        $app = JFactory::getApplication();
        if($app->getName() != 'site') return true;
        
        $app_id = $this->params->get('appid', false);
        if (!$app_id) return true; // no app id provided
        
        // currencies supported by Open Currency 12/2014
        $currencies = json_decode(
			'{
				"AED": "United Arab Emirates Dirham",
				"AFN": "Afghan Afghani",
				"ALL": "Albanian Lek",
				"AMD": "Armenian Dram",
				"ANG": "Netherlands Antillean Guilder",
				"AOA": "Angolan Kwanza",
				"ARS": "Argentine Peso",
				"AUD": "Australian Dollar",
				"AWG": "Aruban Florin", 
				"AZN": "Azerbaijani Manat",
				"BAM": "Bosnia-Herzegovina Convertible Mark",
				"BBD": "Barbadian Dollar",
				"BDT": "Bangladeshi Taka",
				"BGN": "Bulgarian Lev",
				"BHD": "Bahraini Dinar",
				"BIF": "Burundian Franc",
				"BMD": "Bermudan Dollar",
				"BND": "Brunei Dollar",
				"BOB": "Bolivian Boliviano",
				"BRL": "Brazilian Real",
				"BSD": "Bahamian Dollar",
				"BTC": "Bitcoin",
				"BTN": "Bhutanese Ngultrum",
				"BWP": "Botswanan Pula",
				"BYR": "Belarusian Ruble",
				"BZD": "Belize Dollar",
				"CAD": "Canadian Dollar",
				"CDF": "Congolese Franc",
				"CHF": "Swiss Franc",
				"CLF": "Chilean Unit of Account (UF)",
				"CLP": "Chilean Peso",
				"CNY": "Chinese Yuan",
				"COP": "Colombian Peso",
				"CRC": "Costa Rican Colón",
				"CUP": "Cuban Peso",
				"CVE": "Cape Verdean Escudo",
				"CZK": "Czech Republic Koruna",
				"DJF": "Djiboutian Franc",
				"DKK": "Danish Krone",
				"DOP": "Dominican Peso",
				"DZD": "Algerian Dinar",
				"EEK": "Estonian Kroon",
				"EGP": "Egyptian Pound",
				"ERN": "Eritrean Nakfa",
				"ETB": "Ethiopian Birr",
				"EUR": "Euro",
				"FJD": "Fijian Dollar",
				"FKP": "Falkland Islands Pound",
				"GBP": "British Pound Sterling",
				"GEL": "Georgian Lari",
				"GGP": "Guernsey Pound",
				"GHS": "Ghanaian Cedi",
				"GIP": "Gibraltar Pound",
				"GMD": "Gambian Dalasi",
				"GNF": "Guinean Franc",
				"GTQ": "Guatemalan Quetzal",
				"GYD": "Guyanaese Dollar",
				"HKD": "Hong Kong Dollar",
				"HNL": "Honduran Lempira",
				"HRK": "Croatian Kuna",
				"HTG": "Haitian Gourde",
				"HUF": "Hungarian Forint",
				"IDR": "Indonesian Rupiah",
				"ILS": "Israeli New Sheqel",
				"IMP": "Manx pound",
				"INR": "Indian Rupee",
				"IQD": "Iraqi Dinar",
				"IRR": "Iranian Rial",
				"ISK": "Icelandic Króna",
				"JEP": "Jersey Pound",
				"JMD": "Jamaican Dollar",
				"JOD": "Jordanian Dinar",
				"JPY": "Japanese Yen",
				"KES": "Kenyan Shilling",
				"KGS": "Kyrgystani Som",
				"KHR": "Cambodian Riel",
				"KMF": "Comorian Franc",
				"KPW": "North Korean Won",
				"KRW": "South Korean Won",
				"KWD": "Kuwaiti Dinar",
				"KYD": "Cayman Islands Dollar",
				"KZT": "Kazakhstani Tenge",
				"LAK": "Laotian Kip",
				"LBP": "Lebanese Pound",
				"LKR": "Sri Lankan Rupee",
				"LRD": "Liberian Dollar",
				"LSL": "Lesotho Loti",
				"LTL": "Lithuanian Litas",
				"LVL": "Latvian Lats",
				"LYD": "Libyan Dinar",
				"MAD": "Moroccan Dirham",
				"MDL": "Moldovan Leu",
				"MGA": "Malagasy Ariary",
				"MKD": "Macedonian Denar",
				"MMK": "Myanma Kyat",
				"MNT": "Mongolian Tugrik",
				"MOP": "Macanese Pataca",
				"MRO": "Mauritanian Ouguiya",
				"MTL": "Maltese Lira",
				"MUR": "Mauritian Rupee",
				"MVR": "Maldivian Rufiyaa",
				"MWK": "Malawian Kwacha",
				"MXN": "Mexican Peso",
				"MYR": "Malaysian Ringgit",
				"MZN": "Mozambican Metical",
				"NAD": "Namibian Dollar",
				"NGN": "Nigerian Naira",
				"NIO": "Nicaraguan Córdoba",
				"NOK": "Norwegian Krone",
				"NPR": "Nepalese Rupee",
				"NZD": "New Zealand Dollar",
				"OMR": "Omani Rial",
				"PAB": "Panamanian Balboa",
				"PEN": "Peruvian Nuevo Sol",
				"PGK": "Papua New Guinean Kina",
				"PHP": "Philippine Peso",
				"PKR": "Pakistani Rupee",
				"PLN": "Polish Zloty",
				"PYG": "Paraguayan Guarani",
				"QAR": "Qatari Rial",
				"RON": "Romanian Leu",
				"RSD": "Serbian Dinar",
				"RUB": "Russian Ruble",
				"RWF": "Rwandan Franc",
				"SAR": "Saudi Riyal",
				"SBD": "Solomon Islands Dollar",
				"SCR": "Seychellois Rupee",
				"SDG": "Sudanese Pound",
				"SEK": "Swedish Krona",
				"SGD": "Singapore Dollar",
				"SHP": "Saint Helena Pound",
				"SLL": "Sierra Leonean Leone",
				"SOS": "Somali Shilling",
				"SRD": "Surinamese Dollar",
				"STD": "São Tomé and Príncipe Dobra",
				"SVC": "Salvadoran Colón",
				"SYP": "Syrian Pound",
				"SZL": "Swazi Lilangeni",
				"THB": "Thai Baht",
				"TJS": "Tajikistani Somoni",
				"TMT": "Turkmenistani Manat",
				"TND": "Tunisian Dinar",
				"TOP": "Tongan Paʻanga",
				"TRY": "Turkish Lira",
				"TTD": "Trinidad and Tobago Dollar",
				"TWD": "New Taiwan Dollar",
				"TZS": "Tanzanian Shilling",
				"UAH": "Ukrainian Hryvnia",
				"UGX": "Ugandan Shilling",
				"USD": "United States Dollar",
				"UYU": "Uruguayan Peso",
				"UZS": "Uzbekistan Som",
				"VEF": "Venezuelan Bolívar Fuerte",
				"VND": "Vietnamese Dong",
				"VUV": "Vanuatu Vatu",
				"WST": "Samoan Tala",
				"XAF": "CFA Franc BEAC",
				"XAG": "Silver (troy ounce)",
				"XAU": "Gold (troy ounce)",
				"XCD": "East Caribbean Dollar",
				"XDR": "Special Drawing Rights",
				"XOF": "CFA Franc BCEAO",
				"XPF": "CFP Franc",
				"YER": "Yemeni Rial",
				"ZAR": "South African Rand",
				"ZMK": "Zambian Kwacha (pre-2013)",
				"ZMW": "Zambian Kwacha",
				"ZWL": "Zimbabwean Dollar"
			}');

        // get site's normal currency and check that it's in the currency array
        $start_curr = $settings->default_currency ?: 'USD';
        if(!array_key_exists($start_curr, $currencies)) return true; // not in array so bail out
        // if "call for price" obviously we can't do a conversion so bail out
        if($property->formattedprice == JText::_('COM_IPROPERTY_CALL_FOR_PRICE')) return true;

        // else we can do this so get the price
        $price = $property->price;

        // create javascript for ajax request
        $javascript = "
			var ratedata;
            jQuery(document).ready(function($){
				$.getJSON( 'https://openexchangerates.org/api/latest.json?app_id=".$app_id."')
				.done(function( json ) {
					ratedata = json.rates;
					ratedata['USD'] = 1;
				})
				.fail(function( jqxhr, textStatus, error ) {
					var err = textStatus + ', ' + error;
					console.log( 'Request Failed: ' + err );
				});
				
                $('#new_price').html('".addslashes(JText::_('PLG_IP_OPENCURRENCY_SELECT_CURRENCY'))."');		
                $('#currency_select').change(function(){
                    var data = {
						price: '".$price."',
						start: '".$start_curr."',
						end: $('#currency_select').val()
					};
					var dollars = data.price / ratedata[data.start];
                    var final = Math.round( (dollars * ratedata[data.end] + 0.00001) * 100) / 100; // hack to eliminate floating point weirdness
					if (final) $('#new_price').html(final + ' ' + data.end);
                });
             });"; 

        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($javascript);

        $currencies_list   = array();
        $currencies_list[] = JHTML::_('select.option', '', JText::_('PLG_IP_OPENCURRENCY_CURRENCY'), 'id', 'name');

        foreach($currencies as $key => $value){
            $currencies_list[] = JHTML::_('select.option', $key, $value, 'id', 'name');
        }

        $curr_select  = JHTML::_('select.genericlist', $currencies_list, 'currency_select', 'class="inputbox"', 'id', 'name', 'currency_select');

        echo JHtmlBootstrap::addTab('ipMap', 'ipcurrency', JText::_($this->params->get('tabtitle', 'PLG_IP_OPENCURRENCY_CONVERT')));
            echo '
				<div>
					<div class="control-group form-horizontal">
                        <label class="control-label" for="currency_select">'.JText::_('PLG_IP_OPENCURRENCY_SELECT_CURRENCY').'</label>
                        <div class="controls">
                            '.$curr_select.'
                        </div>
					</div>
				</div>
                <hr />
				<div>
					<div class="alert alert-info"><strong>'.JText::_('PLG_IP_OPENCURRENCY_CURRENT_PRICE').':</strong> '.ipropertyHTML::getFormattedPrice($price).'</div>
					<div class="alert alert-success"><strong>'.JText::_('PLG_IP_OPENCURRENCY_NEW_PRICE').':</strong> <span id="new_price"></span></div>
					<div class="small">'.JText::_('PLG_IP_OPENCURRENCY_DISCLAIMER').'</div>
				</div>';
        echo JHtmlBootstrap::endTab();
        return true;
	}
}

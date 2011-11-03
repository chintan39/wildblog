<?php


/** 
 * Class represents one rule using by the definition of the routes. 
 * @see class Router
 */
class RouterRule {
	
	const VALUE_ATTRIBUTE = 1;	// example VALUE_ATTRIBUTE and RULE_GET: domain.com/topic?id=765
	const VALUE_STRING = 2;		// example VALUE_STRING and RULE_PATH: domain.com/article-about-monkeys/
	const VALUE_REGEXP = 3;		// example VALUE_REGEXP and RULE_PATH: domain.com/YYYY-MM-DD/
	
	const RULE_PATH = 1;
	const RULE_GET = 2;
	
	const RULE_SUBACTION = 1;	// example: parts of the page (topic list, ...)
	const RULE_ACTION = 2;		// standardly only one rule RULE_ACTION per request (main page structure) 
	
	var $ruleType, $ruleSpec, $valueType, $valueSpec, $valueIdent=null, $originalSpec;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		
	}
	
	
	/**
	 * Specify the rule type.
	 */
	public function addRule($rType, $rSpec) {
		$this->ruleType = $rType;
		$this->ruleSpec = $rSpec;
	}
	
	
	/**
	 * Specify the rule values.
	 */
	public function addValue($vType, $vSpec, $vIdent=null, $originalSpec=null) {
		$this->valueType = $vType;
		$this->valueSpec = $vSpec;
		if ($vIdent) {
			$this->valueIdent = $vIdent;
		}
		if ($originalSpec) {
			$this->originalSpec = $originalSpec;
		}
	}
	
	
	/**
	 * Specify the rule values.
	 * This method decides the type of the rule from its format.
	 */
	public function addValueSmartly($value) {
		if (preg_match(REGEXP_URL_ATTR, $value, $v)) {
			$this->addValue(RouterRule::VALUE_ATTRIBUTE, $v[2]);
		} elseif (preg_match(REGEXP_URL_IS_REGEXP, $value, $v)) {
			list($regExp, $identificators) = $this->getBracketsIdentificators($v[2]);
			$this->addValue(RouterRule::VALUE_REGEXP, $regExp, $identificators, $v[2]);
		} else {
			$this->addValue(RouterRule::VALUE_STRING, $value);
		}
	}
	
	
	/**
	 * This will extract the identificators of the brackets in the regular expression.
	 * It returns: 
	 * 1) the original regular expression without the identificators 
	 * 2) the identificators with their position. 
	 * @param string $inputRegExp Part of the url between /, for example '^($month=\d{2})($year=\d{4})$'
	 * @return array list(identificators, updatedRegExp)
	 * 		identificator is null or array in the following format: 
	 *			* key is bracket's position
	 *			* value is name of the identifier
	 * 		in the example above it will be array(0 => 'month', 1=>year)
	 */
	private function getBracketsIdentificators($inputRegExp) {
		//echo $inputRegExp;
		$identificators = array();
		$outputRegExp = $inputRegExp;
		// first we extract all brackets
		if (preg_match_all(REGEXP_URL_REGEXP_BRACKETS_EXTRACTION, $inputRegExp, $brackets)) {
			// we go throught brackets' content
			foreach ($brackets[1] as $bracketsPosition => $bracketsContent) {
				// we search identificator in the bracket's content
				if (preg_match(REGEXP_URL_REGEXP_IDENTIFICATOR_EXTRACTION, $bracketsContent, $ident)) {
					// we save the identificator to the key, which is bracket's position
					$identificators[$bracketsPosition] = $ident[1];
					// we need to erase the identificator from the original regexp
					$outputRegExp = str_replace($brackets[1][$bracketsPosition], $ident[2], $outputRegExp);
				} else {
					$identificators[$bracketsPosition] = '';
				}
			}
		}
		return array($outputRegExp, (count($identificators) ? $identificators : null));
	}
}

?>

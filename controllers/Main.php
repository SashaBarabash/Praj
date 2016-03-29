<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller
{
	//array with all regions:
	private $regions = array(
		'Blekinge_län' => '10',
		'Dalarnas_län' => '20',
		'Gotlands_län' => '09',
		'Gävleborgs_län' => '21',
		'Hallands_län' => '13',
		'Jämtlands_län' => '23',
		'Jönköpings_län' => '06',
		'Kalmar_län' => '08',
		'Kronobergs_län' => '07',
		'Norrbottens_län' => '25',
		'Skåne_län' => '12',
		'Stockholms_län' => '01',
		'Södermanlands_län' => '04',
		'Uppsala_län' => '03',
		'Värmlands_län' => '17',
		'Västerbottens_län' => '24',
		'Västernorrlands_Län' => '22',
		'Västmanlands_län' => '19',
		'Västra_Götalands_län' => '14',
		'Örebro_län' => '18',
		'Östergötlands_län' => '05'
	);
	private $titles = array(
		'Bevakningsförfarande' => '19',
		'Efterbevakning' => '15',
		'Efterutdelningsförslag' => '17',
		'Konkursbeslut' => '20',
		'Nedlagd_konkurs' => '18',
		'Slutredovisning' => '57',
		'Undanröjd_konkurs' => '23',
		'Upphävd_konkurs' => '21',
		'Utdelningsförslag' => '16',
		'Övrigt_om_konkurser' => '22',
	);

	//get region info
	public function get_region_info($region)
	{
		$region = $this->regions[urldecode($region)];
		//loading library to work with headers,post data to url
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		//we need special cookie to work - lets get it
		$c = new Requests_Cookie('JSESSIONID', $this->get_cookie());
		$headers = array(
			'Cookie' => $c->formatForHeader()
		);
		//array for links from result pages
		$linksAr = array();
		//lets get it
		$linksAr = array_merge($linksAr, $this->get_region_array($region, $headers));
		//now array with data from pages
		$res_array = array();
		//one time we need to try to get info
		$this->get_info($this->prepare_link($linksAr[0]), $headers);
		//then getting all info to array
		foreach ($linksAr AS $link) {
			$url = $this->prepare_link($link);
//			echo $url.'<br>';
			$res_array[] = $this->get_info($url, $headers);
		}
		//lets check it
//		echo '<pre>';
//		print_r($res_array);
//		echo '</pre>';
	}

	//get region changed
	public function get_region_changed_info($region)
	{
		$region = $this->regions[urldecode($region)];
		//loading library to work with headers,post data to url
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		//we need special cookie to work - lets get it
		$c = new Requests_Cookie('JSESSIONID', $this->get_cookie());
		$headers = array(
			'Cookie' => $c->formatForHeader()
		);
		//array for links from result pages
		$linksAr = array();
		//lets get it
		$linksAr = $this->get_region_array_changed($region, $headers);
		//now array with data from pages
		$res_array = array();
		//one time we need to try to get info
		$this->get_changed_info($this->prepare_link($linksAr[0]), $headers);
		foreach ($linksAr AS $link) {
			$url = $this->prepare_link($link);
			$res_array[] = $this->get_changed_info($url, $headers);
		}
		//lets check it
//		echo '<pre>';
//		print_r($res_array);
//		echo '</pre>';
	}

	//parser for Ämnesområde: Konkurser
	public function get_konkurser()
	{
		//loading library to work with headers,post data to url
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		//we need special cookie to work - lets get it
		$c = new Requests_Cookie('JSESSIONID', $this->get_cookie());
		$headers = array(
			'Cookie' => $c->formatForHeader()
		);
		//array for links from result pages
		$res_array = array();
		foreach ($this->titles AS $key => $val) {
			$linksAr = array();
			//lets get it
			$settings = array(
				'selectedAmnesomrade' => '3',
				'selectedKungorelsetyp' => '19'
			);
			$linksAr = $this->get_links($settings, $headers);
			//now array with data from pages

			//one time we need to try to get info
			//		$this->get_info_konkurser($this->prepare_link($linksAr[0]),$headers);
			foreach ($linksAr AS $link) {
				$url = $this->prepare_link($link);
				$res_array[] = $this->get_info_konkurser_new($url, $headers, $key);
			}
		}
//		//lets check it
//		echo '<pre>';
//		print_r($res_array);
//		echo '</pre>';
	}

	public function get_konkurser_date()
	{
		//loading library to work with headers,post data to url
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		//we need special cookie to work - lets get it
		$c = new Requests_Cookie('JSESSIONID', $this->get_cookie());
		$headers = array(
			'Cookie' => $c->formatForHeader()
		);
		//array for links from result pages
		$linksAr = array();
		//lets get
		$date = new DateTime();
		$date = $date->format('Y-m-d');
		foreach ($this->titles AS $key => $val) {
			$settings = array(
				'selectedAmnesomrade' => '3',
				'selectedKungorelsetyp' => '19',
				'from' => $date,
				'tom' => $date,
			);
			$linksAr = array_merge($linksAr, $this->get_links($settings, $headers));
		}
		//now array with data from pages
		$res_array = array();
		//one time we need to try to get info
//		foreach($linksAr AS $link){
//			$url=$this->prepare_link($link);
//			$res_array[]=$this->get_info_konkurser($url,$headers);
//		}
//		//lets check it
		echo '<pre>';
		print_r($res_array);
		echo '</pre>';
	}

	public function get_konkurser_bp()
	{
		//loading library to work with headers,post data to url
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		//we need special cookie to work - lets get it
		$c = new Requests_Cookie('JSESSIONID', $this->get_cookie());
		$headers = array(
			'Cookie' => $c->formatForHeader()
		);
		//array for links from result pages
		$linksAr = array();
		//lets get it
		$settings = array(
			'selectedAmnesomrade' => '3',
			'selectedKungorelsetyp' => '19'
		);
		$linksAr = $this->get_links($settings, $headers);
		//now array with data from pages
		$res_array = array();
		//one time we need to try to get info
//		$this->get_info_konkurser($this->prepare_link($linksAr[0]),$headers);
		foreach ($linksAr AS $link) {
			$url = $this->prepare_link($link);
			$res_array[] = $this->get_info_konkurser($url, $headers);
		}
//		//lets check it
		echo '<pre>';
		print_r($res_array);
		echo '</pre>';
	}

	public function get_company(){
		try {
			$pdo = new PDO('mysql:host=mysql1.ilait.se;dbname=dbs111676', 'udmybs195027', 'afgd7ig4');
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$pdo->exec('SET NAMES "utf8"');
			echo 'we connected';
		} catch (PDOException $e) {
			echo $error = 'Невозможно подключиться к серверу баз данных.';
			exit();
		}
		try{
			$res = $pdo->query("SELECT * FROM users");
			while ($row = $res->fetch(PDO::FETCH_NUM)){

			}
		}catch (PDOException $e) {

		}
	}
	public function db_add_konkurser($konkurser){
//		$this->load->database();
		$konkurser=array(
			'id'=>'1243',
			'informer'=>'123',
			'type'=>'123',
			'org_number'=>'123',
			'company_name'=>'123',
			'main_text'=>'123',
			'company_creation'=>'123',
			'company_publication'=>'123',
			'entry_creation'=>'123',
			'entry_edited'=>'123',
			'company_id'=>'123',
		);
		$this->db->insert('bw_users_bankruptcies_master_new', $konkurser);
	}
	//---methods to get new add firms---
	//getting cookie from specific page. returns string
	private function get_cookie()
	{
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		$c = new Requests_Cookie_Jar(array('login_uid' =>'something'));
		$request = Requests::get(
			'https://poit.bolagsverket.se/poit/PublikSokKungorelse.do?method=redirect&forward=main.sokkungorelse', // Url
			array(),  // No need to set the headers the Jar does this for us
			array('cookies' => $c) // Pass in the Jar as an option
		);
		return (string)($request->cookies['JSESSIONID']->value);
	}
	//getting info of region. returns array of links
	private function get_region_array($region,$headers){
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		$url = "https://poit.bolagsverket.se/poit/PublikSokKungorelse.do";
		$date=new DateTime();
		$date=$date->format('Y-m-d');
		$post = array(
			'selectedPubliceringsIntervall'=>'6',
			'from'=>$date,
			'tom'=>$date,
			'selectedAmnesomrade'=>'2',
			'selectedKungorelsetyp'=>'4',
			'selectedUnderrubrik'=>'6',
			'selectedLan'=>$region
		);
		$response = Requests::post($url, $headers, $post,array());
		include_once'application/libraries/simple_html_dom.php';
		$html = new simple_html_dom($response->body);
		$elements=$html->find('td[headers=h-diarienummer] a');
		$linksAr=array();
		foreach($elements AS $element){
//			$link='https://poit.bolagsverket.se'.$element->attr['href'];
			$link=$element->attr['href'];
			$linksAr[]=$link;
		}
		return $linksAr;
	}
	//parses webpage - gets info to array
	private function get_info($url,$headers){
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		$response = Requests::post($url, $headers, array(),array());
		include_once'application/libraries/simple_html_dom.php';
		$html = new simple_html_dom($response->body);
		$element=$html->find('div.kungtext',0);
		$lan_string=$html->find('dl.compact',0);
		$result_array=array();
		if(!empty($lan_string->innertext)){
			$lan_string=$lan_string->innertext;
			$preg="/<dt>Län:.+?<\/dd>?/im";
			preg_match($preg,$lan_string,$lan);
			$lan=$lan[0];
			$lan=strip_tags($lan);
			$lan=str_replace('Län:','',$lan);
			$preg2="/\\w.+/im";
			preg_match($preg2,$lan,$lan);
			$lan=$lan[0];
			$result_array=array_merge($result_array,array('Län'=>$lan));
//			echo $lan.'<br>';
		}
		$string='';
		if(!empty($element->innertext)){
			$string=$element->innertext;
			$tmp_ar=explode('<br>',$string);
			foreach($tmp_ar AS $raw){
				$title='';
				$preg="/<b>.+<\/b>/im";
				preg_match($preg,$raw,$title);
				if(isset($title[0])){
					$title=strip_tags($title[0]);
					$title=str_replace(':','',$title);
					$text=str_replace($title,'',$raw);
					$preg2="/\\w.+/im";
					preg_match($preg2,$text,$text);
					$text=$text[0];
					$text=str_replace("b>:",'',$text);
//					echo $title.':'.$text.'<br>';
					$result_array=array_merge($result_array,array($title=>$text));
				}

			}

//			$search_values=array(
//				'Org nr',
//				'Firma',
//				'Säte',
//				'Postadress',
//				'Typ',
//				'Bildat',
//				'Verksamhet',
//				'Räkenskapsår',
//				'Aktiekapital',
//				'Kallelse',
//				'Föreskrift om antal styrelseledamöter/styrelsesuppleanter',
//				'Förbehåll/avvikelser/villkor',
//				'Styrelseledamöter',
//				'Styrelsesuppleanter',
//				'Firmateckning'
//			);
//			foreach($search_values as $value){
//				$value=preg_quote($value);
//				$value=str_replace('/','\/',$value);
//				$temp_ar=$this->searchValue($value,$string);
//				$result_array=array_merge($result_array,$temp_ar);
//			}
			return $result_array;
		}
	}
	//finds single value - returns array with info
	private function searchValue($name,$string){
		$preg="/<b>$name:<\/b>.+?(<br>)/im";
		if($name=='Firmateckning')$preg="/<b>Firmateckning:<\/b>.+/im";
		preg_match($preg,$string,$val);
		$name=str_replace('\/','/',$name);
		if(isset($val[0])){
			$res_str=strip_tags(str_replace("<b>$name:</b>",'',$val[0]));
		}
		else $res_str='';
		$res_ar=array($name=>$res_str);
		return $res_ar;
	}
	//special modifications to url to make it ok for parse :)
	//returns url without special chars
	private function prepare_link($url){
		$first_part=preg_split('/_+?.+%2.+/i',$url);
		$url=str_replace($first_part[0],'',$url);
		$url=str_replace('_presentera=','',$url);
		$url='https://poit.bolagsverket.se/poit/PublikSokKungorelse.do?method=presenteraKungorelse&diarienummer_presentera='.$url;
//		echo $url.'<br>';
		return $url;
	}
	//---END of methods to get new added firms---

	//-- methods to get changed info
	//getting info of region. returns array of links
	private function get_region_array_changed($region,$headers){
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		$url = "https://poit.bolagsverket.se/poit/PublikSokKungorelse.do";
		$date=new DateTime();
		$date=$date->format('Y-m-d');
		$post = array(
			'selectedPubliceringsIntervall'=>'6',
			'from'=>$date,
			'tom'=>$date,
			'selectedAmnesomrade'=>'2',
			'selectedKungorelsetyp'=>'4',
			'selectedUnderrubrik'=>'7',
			'selectedLan'=>$region
		);
		$response = Requests::post($url, $headers, $post,array());
		$page_html=$response->body;
		$links=array();
		$links=$this->get_links_byreg($page_html);
		$reg_check_pages='/<em class="gotopagebuttons">Sida.+ av.+<\/em>?/im';
		preg_match($reg_check_pages,$page_html,$pages_amm);
		if(isset($pages_amm[0])){
			$total_amm=(int)strip_tags(str_replace('Sida 1 av','',$pages_amm[0]));
			$links_tmp=$this->parseLinkPages($total_amm,$headers);
			$links=array_merge($links,$links_tmp);
		}
		return $links;
//		echo '<pre>';
//		var_dump($links);

	}
	private function parseLinkPages($amm,$headers){
		$result_arr=array();
//		echo '<pre>';
		for($i=2;$i<=$amm;$i++){
			$url = "https://poit.bolagsverket.se/poit/PublikSokKungorelse.do";
			$post=array(
				'method#button.gotopage.bottom'=>'Gå till sida',
				'gotopageBottom'=>$i
			);
			$response = Requests::post($url, $headers, $post,array());
			$temp_arr=$this->get_links_byreg($response->body);
			$result_arr=array_merge($result_arr,$temp_arr);
		}
		return $result_arr;
	}
	private function get_changed_info($url,$headers){
		$result_array=array();
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		$response = Requests::post($url, $headers, array(),array());
		$html_page=$response->body;
		$preg_get_comp='/<dl class="compact">.+?<\/dl>/sim';
		preg_match($preg_get_comp,$html_page,$comp);
//		echo $comp[0];

		$lan_string=$comp[0];
		$preg="/<dt>Län:.+?<\/dd>?/ism";
		preg_match($preg,$lan_string,$lan);
		$lan=$lan[0];
		$lan=strip_tags($lan);
		$lan=str_replace('Län:','',$lan);
		$preg2="/\\w.+/im";
		preg_match($preg2,$lan,$lan);
		$lan=$lan[0];
		$result_array=array_merge($result_array,array('Län'=>$lan));

		$ka_string=$comp[0];
		$preg_ka="/<dt>Kungörelsen avser:.+?<\/dd>?/ism";
		preg_match($preg_ka,$ka_string,$ka);
		$ka=$ka[0];
		$ka=strip_tags($ka);
		$ka=str_replace('Kungörelsen avser:','',$ka);
		$preg2="/\\w.+/im";
		preg_match($preg2,$ka,$ka);
		$ka=$ka[0];
		$result_array=array_merge($result_array,array('Kungörelsen avser'=>$ka));

		$preg_get_kung='/<div class="kungtext">.+?<\/div>/sim';
		preg_match($preg_get_kung,$html_page,$kung);
		$kung[0]=str_replace('<div class="kungtext">','',$kung[0]);
		$kung[0]=str_replace('<\/div>','',$kung[0]);
		$tmp_ar=explode('<br>',$kung[0]);
//		var_dump($tmp_ar);
		foreach($tmp_ar AS $raw){
//			echo $raw.'<br>';
			$title='';
			$preg="/<b>.+<\/b>/im";
			preg_match($preg,$raw,$title);
			if(isset($title[0])){
//				echo $title[0];
				$title=strip_tags($title[0]);
				$title=str_replace(':','',$title);
				$text=str_replace($title,'',$raw);
				$preg2="/\\w.+/im";
				preg_match($preg2,$text,$text);
				$text=$text[0];
				$text=str_replace("b>:",'',$text);
//					echo $title.':'.$text.'<br>';
				$result_array=array_merge($result_array,array($title=>$text));
			}
			preg_match('/^[^<b\s].+/im',$kung[0],$add_info);
			if(isset($add_info[0]))
				$result_array=array_merge($result_array,array('info'=>strip_tags($add_info[0])));
		}
		return $result_array;
	}

	//universal methods
	private function get_links($settings,$headers){
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		$url = "https://poit.bolagsverket.se/poit/PublikSokKungorelse.do";
		$post = $settings;
		$response = Requests::post($url, $headers, $post,array());
		$page_html=$response->body;
		$links=array();
		$links=$this->get_links_byreg($page_html);
		$reg_check_pages='/<em class="gotopagebuttons">Sida.+ av.+<\/em>?/im';
		preg_match($reg_check_pages,$page_html,$pages_amm);
		//s
//		if(isset($pages_amm[0])){
//			$total_amm=(int)strip_tags(str_replace('Sida 1 av','',$pages_amm[0]));
//			$links_tmp=$this->parseLinkPages($total_amm,$headers);
//			$links=array_merge($links,$links_tmp);
//		}
		return $links;
	}
	private function get_links_byreg($page){
		$reg_get_info='/".+%2.+">?/im';
		preg_match_all($reg_get_info,$page,$arr);
		$arr=$arr[0];
		for($i=0;$i<count($arr);$i++){
			$arr[$i]=str_replace('"','',$arr[$i]);
			$arr[$i]=str_replace('>','',$arr[$i]);
			$check=str_replace('%2','',$arr[$i]);
			echo $check.'<br>';
		}
		return $arr;
	}
	private function get_str_from_compact($name,$string){
		$preg="/<dt>".$name.":.+?<\/dd>?/ism";
		preg_match($preg,$string,$lan);
		$lan=$lan[0];
		$lan=strip_tags($lan);
		$lan=str_replace($name.':','',$lan);
		$preg2="/\\w.+/im";
		preg_match($preg2,$lan,$lan);
		$lan=$lan[0];
		return array($name=>$lan);
	}

	private function get_info_konkurser($url,$headers){
		$result_array=array();
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		$response = Requests::post($url, $headers, array(),array());
		$html_page=$response->body;
		$preg_get_comp='/<dl class="compact">.+?<\/dl>/sim';
		preg_match($preg_get_comp,$html_page,$comp);
//		echo $comp[0];

		$lan_string=$comp[0];
		$result_array=array_merge($result_array,$this->get_str_from_compact('Kungörelsen avser',$lan_string));
		$result_array=array_merge($result_array,$this->get_str_from_compact('Senaste bevakningsdatum',$lan_string));
		$result_array=array_merge($result_array,$this->get_str_from_compact('Publiceringsdatum',$lan_string));
		$result_array=array_merge($result_array,$this->get_str_from_compact('Kungörelse-id',$lan_string));
		$result_array=array_merge($result_array,$this->get_str_from_compact('Uppgiftslämnare',$lan_string));

		$preg_get_kung='/<div class="kungtext">.+?<\/div>/sim';
		preg_match($preg_get_kung,$html_page,$kung);
		$kung[0]=str_replace('<div class="kungtext">','',$kung[0]);
		$kung[0]=str_replace('<\/div>','',$kung[0]);
		$kung[0]=strip_tags($kung[0]);
		$kung[0]=preg_replace("/\\s{2,}/", "", $kung[0]);
		$temp_ar=explode(',',$result_array['Kungörelsen avser']);
		$result_array=array_merge($result_array,array('info'=>$kung[0]));
		//getting current date
		$cr_date=new DateTime();
		$cr_date=$cr_date->getTimestamp();
		//converting company_creation date
		$cc_date=DateTime::createFromFormat('Y-m-d',$result_array['Senaste bevakningsdatum']);
		$cc_date=$cc_date->getTimestamp();
		//converting company_publication date
		$cp_date=0;
		if(isset($result_array['Publiceringsdatum'])){
			$result_array['Publiceringsdatum']=preg_replace("/\\s/",'',$result_array['Publiceringsdatum']);
			$cp_date=DateTime::createFromFormat('Y-m-d',$result_array['Publiceringsdatum']);
			$cp_date=$cp_date->getTimestamp();
		}
		$konkurser=array(
			'id'=>$result_array['Kungörelse-id'],
			'informer'=>$result_array['Uppgiftslämnare'],
			'type'=>'1',
			'org_number'=>$temp_ar[0],
			'company_name'=>$temp_ar[1],
			'main_text'=>$result_array['info'],
			'company_creation'=>$cc_date,
			'company_publication'=>$cp_date,
			'entry_creation'=>$cr_date,
//			'company_id'=>'123',
		);
		$this->db->insert('bw_users_bankruptcies_master_new', $konkurser);
		return $result_array;
	}
	private function get_info_konkurser_new($url,$headers,$type){
		$result_array=array();
		$this->load->library('PHPRequests');
		Requests::register_autoloader();
		$response = Requests::post($url, $headers, array(),array());
		$html_page=$response->body;
		$preg_get_comp='/<dl class="compact">.+?<\/dl>/sim';
		preg_match($preg_get_comp,$html_page,$comp);
//		echo $comp[0];

		$lan_string=$comp[0];
		$result_array=array_merge($result_array,$this->get_str_from_compact('Kungörelsen avser',$lan_string));
		$result_array=array_merge($result_array,$this->get_str_from_compact('Senaste bevakningsdatum',$lan_string));
		$result_array=array_merge($result_array,$this->get_str_from_compact('Publiceringsdatum',$lan_string));
		$result_array=array_merge($result_array,$this->get_str_from_compact('Kungörelse-id',$lan_string));
		$result_array=array_merge($result_array,$this->get_str_from_compact('Uppgiftslämnare',$lan_string));

		$preg_get_kung='/<div class="kungtext">.+?<\/div>/sim';
		preg_match($preg_get_kung,$html_page,$kung);
		$kung[0]=str_replace('<div class="kungtext">','',$kung[0]);
		$kung[0]=str_replace('<\/div>','',$kung[0]);
		$kung[0]=strip_tags($kung[0]);
		$kung[0]=preg_replace("/\\s{2,}/", "", $kung[0]);
		$temp_ar=explode(',',$result_array['Kungörelsen avser']);
		$result_array=array_merge($result_array,array('info'=>$kung[0]));
		//getting current date
		$cr_date=new DateTime();
		$cr_date=$cr_date->format('Y-m-d');

//		$konkurser=array(
//			'id'=>$result_array['Kungörelse-id'],
//			'informer'=>$result_array['Uppgiftslämnare'],
//			'type'=>'1',
//			'org_number'=>$temp_ar[0],
//			'company_name'=>$temp_ar[1],
//			'main_text'=>$result_array['info'],
//			'company_creation'=>$cc_date,
//			'company_publication'=>$cp_date,
//			'entry_creation'=>$cr_date,
////			'company_id'=>'123',
//
		$informer_id=$this->get_sub_table_id('bw_bankruptcies_informers',$result_array['Uppgiftslämnare']);
		$type_id=$this->get_sub_table_id('bw_bankruptcies_typer',$type);
		$bankruptcies_date=NULL;
		if(isset($result_array['Senaste bevakningsdatum']))$bankruptcies_date=$result_array['Senaste bevakningsdatum'];

		$konkurser=array(
			'publication_id'=>$result_array['Kungörelse-id'],
			'informer_id'=>$informer_id,
			'type_id'=>$type_id,
			'company_id'=>NULL,
			'registration_number'=>$temp_ar[0],
			'company_name'=>$temp_ar[1],
			'text'=>$result_array['info'],
			'bankruptcies_date'=>$result_array['Senaste bevakningsdatum'],
			'publication_date'=>$result_array['Publiceringsdatum'],
			'publication_date'=>$result_array['Publiceringsdatum'],
			'created'=>$cr_date,
			'source'=>'Bolagsverket',
		);
//		echo '<pre>';
//		var_dump($konkurser);
//		echo '</pre>';
		$this->db->insert('bw_users_bankruptcies_master', $konkurser);
		return $result_array;
	}
	private function get_sub_table_id($table,$name){
//		$name='Malmö tingsrätt';
		$check=$this->db->select('id')->get_where($table,array('name'=>$name));
		$check=$check->result_array();
		$id=0;
		if(!isset($check[0])){
			$this->db->insert($table,array('name'=>$name));
			$id=$this->db->insert_id();
		}
		else {
			$id=$check[0]['id'];
		}
		return $id;
	}
}

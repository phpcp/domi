<?php

namespace App\Admin\Controllers;

use App\Models\Wcountryz;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Facades\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
class WcountryzController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '国家列表';
    public function wcountryz_list(Content $content)
    {
        noPjax();
        return $content->title('国家列表')
            ->description('国家列表')
            ->view('wcountryz.list');
    }
    public function wcountryz_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;
        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'desc':$request->SortOrder;
        $Wcountryz = Wcountryz::orderBy($SortField,$SortOrder)->orderBy('id','desc');
        $count = $Wcountryz->count();
        $Wcountryz = $Wcountryz->offset($page)->limit($limit);
        $WcountryzList = $Wcountryz->get()->toArray();

        $add = Admin::user()->can('wcountryz.add');
        $form = Admin::user()->can('wcountryz.form');
        $array_iso = $this->array_iso();
        foreach ($WcountryzList as $key => $value) {
            $WcountryzList[$key]['add'] = $add;
            $WcountryzList[$key]['form'] = $form;
            $WcountryzList[$key]['code_title'] = array_search($value['code'], $array_iso).'==>'.$value['code'];
        }
        return return_json(0,'获取成功！',$WcountryzList,$count);
    }
    public function wcountryz_add()
    {
        return view('wcountryz.add')
        ->with('title','国家管理');
    }
    public function wcountryz_form(Request $request)
    {
        $data = $request->post();
        if( empty($data['ids'])){
            unset($data['ids']);
            $id = Wcountryz::insertGetId($data);
        }else{
            $id = $data['ids'];
            unset($data['ids']); 
            Wcountryz::where('id',$id)->update($data);
        }
        return return_json(0,'操作成功！',$id);
    }
    public function wcountryz_show(Request $request)
    {
        $data = $request->input('data');
        $limit = 10;
        $count = Wcountryz::where('status','=',1)->count();
        $pages = ceil($count / $limit);

        for ($i = 0; $i < $pages; $i++) { 
            $arrayRes[$i]['parentId'] = 0;
            $arrayRes[$i]['disabled'] = true;
            $arrayRes[$i]['id'] = '-'.($i + 1);
            $arrayRes[$i]['title'] = '第'.($i + 1).'页';
            $page = $i * $limit;
            $Wcountryz = Wcountryz::where('status','=',1)->orderBy('sort','desc')->orderBy('id','desc');
            $Wcountryz = $Wcountryz->offset($page)->limit($limit);
            $WcountryzList = $Wcountryz->get()->toArray();
            
            foreach ($WcountryzList as $key => $value) {
                $arrayRes[$i]['children'][$key]['parentId'] = '-'.($i + 1);
                $arrayRes[$i]['children'][$key]['disabled'] = false; 
                $arrayRes[$i]['children'][$key]['id'] = $value['id']; 
                $arrayRes[$i]['children'][$key]['title'] = $value['name'];
            }
        }
        return return_json(0,'操作成功！',$arrayRes);
    }
    public function wlanguage_iso(Request $request)
    {
        $letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $array_iso = $this->array_iso();
        $Array = [];
        foreach ($letter as $key => $value) {
            $Array[$key]['id'] = $value;
            $Array[$key]['title'] = $value;
            $Array[$key]['spread'] = false;
            $Array[$key]['parentId'] = -1;
            $Array[$key]['children'] = [];
            $getFirstCharter = '';
            $arr = [];
            foreach ($array_iso as $k => $v) {
                $getFirstCharter = $this->getFirstCharter($k);
                if( empty($getFirstCharter)){
                    if( $k == '斐济'){
                        $getFirstCharter = 'F';
                    }else if ( $k == '瑙鲁'){
                        $getFirstCharter = 'N';
                    }else if( $k == '梵蒂岗' ){
                        $getFirstCharter = 'V';
                    }
                }
                if( $getFirstCharter == $value ){
                    $arr['id'] = $v;
                    $arr['title'] = $k.'==>'.$v;
                    $arr['spread'] = false;
                    $arr['parentId'] = $value;
                    array_push($Array[$key]['children'], $arr);
                    unset($array_iso[$k]);
                }
            }
        }
        return return_json(0,'操作成功',$Array);
    }
    public function array_iso()
    {
        return [
            "阿鲁巴" => "AA"
            ,"安道尔" => "AD"
            ,"阿联酋" => "AE"
            ,"阿富汗" => "AF"
            ,"安提瓜和巴布达" => "AG"
            ,"阿尔巴尼亚" => "AL"
            ,"亚美尼亚" => "AM"
            ,"荷属安德列斯" => "AN"
            ,"安哥拉" => "AO"
            ,"南极洲" => "AQ"
            ,"阿根廷" => "AR"
            ,"东萨摩亚" => "AS"
            ,"奥地利" => "AT"
            ,"澳大利亚" => "AU"
            ,"阿塞拜疆" => "AZ"
            ,"安圭拉岛" => "Av"
            ,"波黑" => "BA"
            ,"巴巴多斯" => "BB"
            ,"孟加拉" => "BD"
            ,"比利时" => "BE"
            ,"布基纳法索" => "BF"
            ,"保加利亚" => "BG"
            ,"巴林" => "BH"
            ,"布隆迪" => "BI"
            ,"贝宁" => "BJ"
            ,"百慕大" => "BM"
            ,"文莱布鲁萨兰" => "BN"
            ,"玻利维亚" => "BO"
            ,"巴西" => "BR"
            ,"巴哈马" => "BS"
            ,"不丹" => "BT"
            ,"布韦岛" => "BV"
            ,"博茨瓦纳" => "BW"
            ,"白俄罗斯" => "BY"
            ,"伯里兹" => "BZ"
            ,"加拿大" => "CA"
            ,"柬埔寨" => "KH"
            ,"可可斯群岛" => "CC"
            ,"刚果" => "CG"
            ,"中非" => "CF"
            ,"瑞士" => "CH"
            ,"象牙海岸" => "CI"
            ,"库克群岛" => "CK"
            ,"智利" => "CL"
            ,"喀麦隆" => "CM"
            ,"中国" => "CN"
            ,"哥伦比亚" => "CO"
            ,"哥斯达黎加" => "CR"
            ,"捷克斯洛伐克" => "CS"
            ,"古巴" => "CU"
            ,"佛得角" => "CV"
            ,"圣诞岛" => "CX"
            ,"塞普路斯" => "CY"
            ,"捷克" => "CZ"
            ,"德国" => "DE"
            ,"吉布提" => "DJ"
            ,"丹麦" => "DK"
            ,"多米尼加共和国" => "DM"
            ,"多米尼加联邦" => "DO"
            ,"阿尔及利亚" => "DZ"
            ,"厄瓜多尔" => "EC"
            ,"爱沙尼亚" => "EE"
            ,"埃及" => "EG"
            ,"西撒哈拉" => "EH"
            ,"厄立特里亚" => "ER"
            ,"西班牙" => "ES"
            ,"埃塞俄比亚" => "ET"
            ,"芬兰" => "FI"
            ,"斐济" => "FJ"
            ,"福兰克群岛" => "FK"
            ,"米克罗尼西亚" => "FM"
            ,"法罗群岛" => "FO"
            ,"法国" => "FR"
            ,"法国-主教区" => "FX"
            ,"加蓬" => "GA"
            ,"英国" => "UK"
            ,"格林纳达" => "GD"
            ,"格鲁吉亚" => "GE"
            ,"法属圭亚那" => "GF"
            ,"加纳" => "GH"
            ,"直布罗陀" => "GI"
            ,"格陵兰岛" => "GL"
            ,"冈比亚" => "GM"
            ,"几内亚" => "GN"
            ,"法属德洛普群岛" => "GP"
            ,"赤道几内亚" => "GQ"
            ,"希腊" => "GR"
            ,"S. Georgia and S. Sandwich Isls." => "GS"
            ,"危地马拉" => "GT"
            ,"关岛" => "GU"
            ,"几内亚比绍" => "GW"
            ,"圭亚那" => "GY"
            ,"中国香港特区" => "HK"
            ,"赫德和麦克唐纳群岛" => "HM"
            ,"洪都拉斯" => "HN"
            ,"克罗地亚" => "HR"
            ,"海地" => "HT"
            ,"匈牙利" => "HU"
            ,"印度尼西亚" => "ID"
            ,"爱尔兰" => "IE"
            ,"以色列" => "IL"
            ,"印度" => "IN"
            ,"英属印度洋领地" => "IO"
            ,"伊拉克" => "IQ"
            ,"伊朗" => "IR"
            ,"冰岛" => "IS"
            ,"意大利" => "IT"
            ,"牙买加" => "JM"
            ,"约旦" => "JO"
            ,"日本" => "JP"
            ,"肯尼亚" => "KE"
            ,"吉尔吉斯斯坦" => "KG"
            ,"基里巴斯" => "KI"
            ,"科摩罗" => "KM"
            ,"圣基茨和尼维斯" => "KN"
            ,"韩国" => "KP"
            ,"朝鲜" => "KR"
            ,"科威特" => "KW"
            ,"开曼群岛" => "KY"
            ,"哈萨克斯坦" => "KZ"
            ,"老挝" => "LA"
            ,"黎巴嫩" => "LB"
            ,"圣卢西亚" => "LC"
            ,"列支顿士登" => "LI"
            ,"斯里兰卡" => "LK"
            ,"利比里亚" => "LR"
            ,"莱索托" => "LS"
            ,"立陶宛" => "LT"
            ,"卢森堡" => "LU"
            ,"拉托维亚" => "LV"
            ,"利比亚" => "LY"
            ,"摩洛哥" => "MA"
            ,"摩纳哥" => "MC"
            ,"摩尔多瓦" => "MD"
            ,"马达加斯加" => "MG"
            ,"马绍尔群岛" => "MH"
            ,"马其顿" => "MK"
            ,"马里" => "ML"
            ,"缅甸" => "MM"
            ,"蒙古" => "MN"
            ,"中国澳门特区" => "MO"
            ,"北马里亚纳群岛" => "MP"
            ,"法属马提尼克群岛" => "MQ"
            ,"毛里塔尼亚" => "MR"
            ,"蒙塞拉特岛" => "MS"
            ,"马耳他" => "MT"
            ,"毛里求斯" => "MU"
            ,"马尔代夫" => "MV"
            ,"马拉维" => "MW"
            ,"墨西哥" => "MX"
            ,"马来西亚" => "MY"
            ,"莫桑比克" => "MZ"
            ,"纳米比亚" => "NA"
            ,"新卡里多尼亚" => "NC"
            ,"尼日尔" => "NE"
            ,"诺福克岛" => "NF"
            ,"尼日利亚" => "NG"
            ,"尼加拉瓜" => "NI"
            ,"荷兰" => "NL"
            ,"挪威" => "NO"
            ,"尼泊尔" => "NP"
            ,"瑙鲁" => "NR"
            ,"中立区(沙特-伊拉克间)" => "NT"
            ,"纽爱" => "NU"
            ,"新西兰" => "NZ"
            ,"阿曼" => "OM"
            ,"巴拿马" => "PA"
            ,"秘鲁" => "PE"
            ,"法属玻里尼西亚" => "PF"
            ,"巴布亚新几内亚" => "PG"
            ,"菲律宾" => "PH"
            ,"巴基斯坦" => "PK"
            ,"波兰" => "PL"
            ,"圣皮艾尔和密克隆群岛" => "PM"
            ,"皮特克恩岛" => "PN"
            ,"波多黎各" => "PR"
            ,"葡萄牙" => "PT"
            ,"帕劳" => "PW"
            ,"巴拉圭" => "PY"
            ,"卡塔尔" => "QA"
            ,"法属尼留旺岛" => "RE"
            ,"罗马尼亚" => "RO"
            ,"俄罗斯" => "RU"
            ,"卢旺达" => "RW"
            ,"沙特阿拉伯" => "SA"
            ,"塞舌尔" => "SC"
            ,"苏丹" => "SD"
            ,"瑞典" => "SE"
            ,"新加坡" => "SG"
            ,"圣赫勒拿" => "SH"
            ,"斯罗文尼亚" => "SI"
            ,"斯瓦尔巴特和扬马延岛" => "SJ"
            ,"斯洛伐克" => "SK"
            ,"塞拉利昂" => "SL"
            ,"圣马力诺" => "SM"
            ,"塞内加尔" => "SN"
            ,"索马里" => "SO"
            ,"苏里南" => "SR"
            ,"圣多美和普林西比" => "ST"
            ,"前苏联" => "SU"
            ,"萨尔瓦多" => "SV"
            ,"叙利亚" => "SY"
            ,"斯威士兰" => "SZ"
            ,"所罗门群岛" => "Sb"
            ,"特克斯和凯科斯群岛" => "TC"
            ,"乍得" => "TD"
            ,"法国南部领地" => "TF"
            ,"多哥" => "TG"
            ,"泰国" => "TH"
            ,"塔吉克斯坦" => "TJ"
            ,"托克劳群岛" => "TK"
            ,"土库曼斯坦" => "TM"
            ,"突尼斯" => "TN"
            ,"汤加" => "TO"
            ,"东帝汶" => "TP"
            ,"土尔其" => "TR"
            ,"特立尼达和多巴哥" => "TT"
            ,"图瓦卢" => "TV"
            ,"中国台湾省" => "TW"
            ,"坦桑尼亚" => "TZ"
            ,"乌克兰" => "UA"
            ,"乌干达" => "UG"
            ,"美国海外领地" => "UM"
            ,"美国" => "US"
            ,"乌拉圭" => "UY"
            ,"乌兹别克斯坦" => "UZ"
            ,"梵蒂岗" => "VA"
            ,"圣文森特和格陵纳丁斯" => "VC"
            ,"委内瑞拉" => "VE"
            ,"英属维京群岛" => "VG"
            ,"美属维京群岛" => "VI"
            ,"越南" => "VN"
            ,"瓦努阿鲁" => "VU"
            ,"瓦里斯和福图纳群岛" => "WF"
            ,"西萨摩亚" => "WS"
            ,"也门" => "YE"
            ,"马约特岛" => "YT"
            ,"南斯拉夫" => "YU"
            ,"南非" => "ZA"
            ,"赞比亚" => "ZM"
            ,"扎伊尔" => "ZR"
            ,"津巴布韦" => "ZW"
        ];
    }
    public function getFirstCharter($str)
    {
        if(empty($str)){return '';}
        $fchar=ord($str{0});
        if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'GBK//TRANSLIT//IGNORE', $str);
        $s2 = iconv('GBK', 'UTF-8//TRANSLIT//IGNORE', $s1);
        $s=$s2==$str?$s1:$str;
        $asc=ord($s{0})*256+ord($s{1})-65536;
        if($asc>=-20319&&$asc<=-20284) return 'A';
        if($asc>=-20283&&$asc<=-19776) return 'B';
        if($asc>=-19775&&$asc<=-19219) return 'C';
        if($asc>=-19218&&$asc<=-18711) return 'D';
        if($asc>=-18710&&$asc<=-18527) return 'E';
        if($asc>=-18526&&$asc<=-18240) return 'F';
        if($asc>=-18239&&$asc<=-17923) return 'G';
        if($asc>=-17922&&$asc<=-17418) return 'H';
        if($asc>=-17417&&$asc<=-16475) return 'J';
        if($asc>=-16474&&$asc<=-16213) return 'K';
        if($asc>=-16212&&$asc<=-15641) return 'L';
        if($asc>=-15640&&$asc<=-15166) return 'M';
        if($asc>=-15165&&$asc<=-14923) return 'N';
        if($asc>=-14922&&$asc<=-14915) return 'O';
        if($asc>=-14914&&$asc<=-14631) return 'P';
        if($asc>=-14630&&$asc<=-14150) return 'Q';
        if($asc>=-14149&&$asc<=-14091) return 'R';
        if($asc>=-14090&&$asc<=-13319) return 'S';
        if($asc>=-13318&&$asc<=-12839) return 'T';
        if($asc>=-12838&&$asc<=-12557) return 'W';
        if($asc>=-12556&&$asc<=-11848) return 'X';
        if($asc>=-11847&&$asc<=-11056) return 'Y';
        if($asc>=-11055&&$asc<=-10247) return 'Z';
        return null;
    }
}

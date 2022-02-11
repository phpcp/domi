<?php

namespace App\Admin\Controllers;

use App\Models\Wlanguage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;
class WlanguageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '语言设置';

    public function wlanguage_list(Content $content)
    {
        noPjax();
        return $content->title('语言设置')
            ->description('语言设置')
            ->view('wlanguage.list');
    }
    public function wlanguage_ajax(Request $request)
    {
        $limit = $request->limit;
        $page = ($request->page - 1) * $limit;

        $SortField = empty($request->SortField)?'sort':$request->SortField;
        $SortOrder = empty($request->SortOrder)?'asc':$request->SortOrder;

        $wlanguage = Wlanguage::orderBy($SortField,$SortOrder)->orderBy('id','desc')->offset($page)->limit($limit)->get()->toArray();

        $form = Admin::user()->can('wlanguage.form');
        foreach ($wlanguage as $key => $value) {
            $wlanguage[$key]['form'] = $form;
            $wlanguage[$key]['baidu_name_title'] = $value['baidu_name'].'==>'.$value['baidu_iso'];
            $wlanguage[$key]['baidu_name'] = $value['baidu_name'].'==>'.$value['baidu_iso'];
        }
        return return_json(0,'获取成功！',$wlanguage,Wlanguage::count());
    }
    public function wlanguage_add()
    {
        return view('wlanguage.add')
        ->with('title','语言设置');
    }
    public function wlanguage_form(Request $request)
    {
        $data = $request->post();
        if(!empty($data['baidu_name'])){
            $baidu_name = explode('==>',$data['baidu_name']);
            $data['baidu_name'] = $baidu_name[0];
            $data['baidu_iso'] = $baidu_name[1];
        }
        unset($data['baidu_name']);
        if( empty($data['ids'])){
            unset($data['ids']);
            $id = Wlanguage::insertGetId($data);
        }else{
            $id = $data['ids'];
            unset($data['ids']); 
            Wlanguage::where('id',$id)->update($data);
        }
        return return_json(0,'操作成功！',$id);
    }
    public function wlanguage_show(Request $request)
    {
        $data = $request->input('data');
        $wlanguage = Wlanguage::where('status',1)->orderBy('status','asc')->orderBy('sort','asc')->orderBy('id','desc')->select('id','name as title','status')->get()->toArray();
        if( empty($wlanguage)){
            return return_json(1,'没有数据!');
        }else{

            foreach ($wlanguage as $key => $value) {
                if( $value['status'] != 1){
                    $wlanguage[$key]['disabled'] = true;
                }else{
                    if( !empty($data) ){
                        $disabled = explode(',', $data);
                        if( in_array( $value['id'],$disabled ) ){
                            $wlanguage[$key]['disabled'] = true;
                        }
                    }
                }
                $wlanguage[$key]['parentId'] = 0;
                $wlanguage[$key]['checkArr'] = "3";
            }
            $Array[0]['id'] = 0;
            $Array[0]['title'] = '顶级权限';
            $Array[0]['status'] = 1;
            $Array[0]['spread'] = true;
            $Array[0]['parentId'] = 0;
            $Array[0]['children'] = $wlanguage;
            return return_json(0,'操作成功',$Array);
        }
    }
    public function wlanguage_baidu(Request $request)
    {
        $letter = ['A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','W','X','Y','Z'];
        $array_baidu = $this->array_baidu();
        $Array = [];
        foreach ($letter as $key => $value) {
            $Array[$key]['id'] = $value;
            $Array[$key]['title'] = $value;
            $Array[$key]['spread'] = false;
            $Array[$key]['parentId'] = -1;
            $Array[$key]['children'] = [];
            $getFirstCharter = '';
            $arr = [];
            foreach ($array_baidu as $k => $v) {
                $getFirstCharter = $this->getFirstCharter($k);
                if( empty($getFirstCharter)){
                    if( $k == '鞑靼语'){
                        $getFirstCharter = 'D';
                    }else if ( $k == '俾路支语'){
                        $getFirstCharter = 'B';
                    }else if( $k == '梵语' ){
                        $getFirstCharter = 'F';
                    }else if( $k == '巽他语'){
                        $getFirstCharter = 'X';
                    }
                }
                if( $getFirstCharter == $value ){
                    $arr['id'] = $k.'==>'.$v;
                    $arr['title'] = $k.'==>'.$v;
                    $arr['spread'] = false;
                    $arr['parentId'] = $value;
                    array_push($Array[$key]['children'], $arr);
                    unset($array_baidu[$k]);
                }
            }
        }
        return return_json(0,'操作成功',$Array);
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
    public function array_baidu()
    {
        return [
            '阿拉伯语'=>'ara',
            '爱尔兰语'=>'gle',
            '奥克语'=>'oci',
            '阿尔巴尼亚语' => 'alb',
            '阿尔及利亚阿拉伯语'=>'arq',
            '阿肯语'=> 'aka',
            '阿拉贡语'=>'arg',
            '阿姆哈拉语'=> 'amh',
            '阿萨姆语'=>'asm',
            '艾马拉语'=>'aym',
            '阿塞拜疆语'=>'aze',
            '阿斯图里亚斯语'=> 'ast',
            '奥塞梯语'=>'oss',
            '爱沙尼亚语'=>'est',
            '奥杰布瓦语'=>'oji',
            '奥里亚语'=>'ori',
            '奥罗莫语'=>'orm',
            '波兰语'=>'pl',
            '波斯语'=>'per',
            '布列塔尼语'=>'bre',
            '巴什基尔语'=>'bak',
            '巴斯克语'=>'baq',
            '巴西葡萄牙语'=>'pot',
            '白俄罗斯语'=>'bel',
            '柏柏尔语'=>'ber',
            '邦板牙语'=>'pam',
            '保加利亚语'=>'bul',
            '北方萨米语'=>'sme',
            '北索托语'=>'ped',
            '本巴语'=>'bem',
            '比林语'=>'bli',
            '比斯拉马语'=>'bis',
            '俾路支语'=>'bal',   
            '冰岛语'=>'ice',   
            '波斯尼亚语'=>'bos',
            '博杰普尔语'=>'bho',
            '楚瓦什语'=>'chv',  
            '聪加语'=>'tso',
            '丹麦语'=>'dan',    
            '德语'=>'de',  
            '鞑靼语'=>'tat', 
            '掸语'=>'sha',    
            '德顿语'=>'tet',    
            '迪维希语'=>'div', 
            '低地德语'=>'log',  
            '俄语'=>'ru',
            '法语'=>'fra',   
            '菲律宾语'=>'fil',   
            '芬兰语'=>'fin',
            '梵语'=>'san',   
            '弗留利语'=>'fri',   
            '富拉尼语'=>'ful',
            '法罗语'=>'fao', 
            '盖尔语'=>'gla',   
            '刚果语'=>'kon',   
            '高地索布语'=>'ups',
            '高棉语'=>'hkm',   
            '格陵兰语'=>'kal',  
            '格鲁吉亚语'=>'geo',
            '古吉拉特语'=>'guj',   
            '古希腊语'=>'gra',   
            '古英语'=>'eno',
            '瓜拉尼语'=>'grn',
            '韩语'=>'kor',   
            '荷兰语'=>'nl',   
            '胡帕语'=>'hup',
            '哈卡钦语'=>'hak',               
            '海地语'=>'ht',
            '豪萨语'=>'hau',   
            '黑山语'=>'mot',
            '吉尔吉斯语'=>'kir',   
            '加利西亚语'=>'glg',   
            '加拿大法语'=>'frn',
            '加泰罗尼亚语'=>'cat',   
            '捷克语'=>'cs',
            '卡拜尔语'=>'kab',   
            '卡纳达语'=>'kan',   
            '卡努里语'=>'kau',
            '卡舒比语'=>'kah',   
            '康瓦尔语'=>'cor',   
            '科萨语'=>'xho',
            '科西嘉语'=>'cos',   
            '克里克语'=>'cre',   
            '克里米亚鞑靼语'=>'cri',
            '克林贡语'=>'kli',   
            '克罗地亚语'=>'hrv',   
            '克丘亚语'=>'que',
            '克什米尔语'=>'kas',   
            '孔卡尼语'=>'kok',   
            '库尔德语'=>'kur',
            '拉丁语'=>'lat',   
            '老挝语'=>'lao',   
            '罗马尼亚语'=>'rom',
            '拉特加莱语'=>'lag',   
            '拉脱维亚语'=>'lav',   
            '林堡语'=>'lim',
            '林加拉语'=>'lin',   
            '卢干达语'=>'lug',   
            '卢森堡语'=>'ltz',
            '卢森尼亚语'=>'ruy',   
            '卢旺达语'=>'kin',   
            '立陶宛语'=>'lit',
            '罗曼什语'=>'roh',   
            '罗姆语'=>'ro',   
            '逻辑语'=>'loj',
            '马来语'=>'may',   
            '缅甸语'=>'bur',   
            '马拉地语'=>'mar',
            '马拉加斯语'=>'mg',   
            '马拉雅拉姆语'=>'mal',   
            '马其顿语'=>'mac',
            '马绍尔语'=>'mah',   
            '迈蒂利语'=>'mai',   
            '曼克斯语'=>'glv',
            '毛里求斯克里奥尔语'=>'mau',   
            '毛利语'=>'mao',   
            '孟加拉语'=>'ben',
            '马耳他语'=>'mlt',   
            '苗语'=>'hmn',
            '挪威语'=>'nor',   
            '那不勒斯语'=>'nea',   
            '南恩德贝莱语'=>'nbl',
            '南非荷兰语'=>'afr',   
            '南索托语'=>'sot',   
            '尼泊尔语'=>'nep',
            '葡萄牙语'=>'pt',   
            '旁遮普语'=>'pan',   
            '帕皮阿门托语'=>'pap',
            '普什图语'=>'pus',
            '齐切瓦语'=>'nya',   
            '契维语'=>'twi',   
            '切罗基语'=>'chr',
            '日语'=>'jp',   
            '瑞典语'=>'swe',
            '萨丁尼亚语'=>'srd',   
            '萨摩亚语'=>'sm',   
            '塞尔维亚-克罗地亚语'=>'sec',
            '塞尔维亚语'=>'srp',   
            '桑海语'=>'sol',   
            '僧伽罗语'=>'sin',
            '世界语'=>'epo',   
            '书面挪威语'=>'nob',   
            '斯洛伐克语'=>'sk',
            '斯洛文尼亚语'=>'slo',   
            '斯瓦希里语'=>'swa',           
            '索马里语'=>'som',
            '泰语'=>'th',   
            '土耳其语'=>'tr',   
            '塔吉克语'=>'tgk',
            '泰米尔语'=>'tam',   
            '他加禄语'=>'tgl',   
            '提格利尼亚语'=>'tir',
            '泰卢固语'=>'tel',   
            '突尼斯阿拉伯语'=>'tua',   
            '土库曼语'=>'tuk',
            '乌克兰语'=>'ukr',   
            '瓦隆语'=>'wln',   
            '威尔士语'=>'wel',
            '文达语'=>'ven',   
            '沃洛夫语'=>'wol',   
            '乌尔都语'=>'urd',
            '西班牙语'=>'spa',   
            '希伯来语'=>'heb',   
            '希腊语'=>'el',
            '匈牙利语'=>'hu',   
            '西弗里斯语'=>'fry',   
            '西里西亚语'=>'sil',
            '希利盖农语'=>'hil',   
            '下索布语'=>'los',   
            '夏威夷语'=>'haw',
            '新挪威语'=>'nno',   
            '西非书面语'=>'nqo',   
            '信德语'=>'snd',
            '修纳语'=>'sna',   
            '宿务语'=>'ceb',   
            '叙利亚语'=>'syr',
            '巽他语'=>'sun',
            '英语'=>'en',   
            '印地语'=>'hi',   
            '印尼语'=>'id',
            '意大利语'=>'it',   
            '越南语'=>'vie',   
            '意第绪语'=>'yid',
            '因特语'=>'ina',   
            '亚齐语'=>'ach',   
            '印古什语'=>'ing',
            '伊博语'=>'ibo',   
            '伊多语'=>'ido',   
            '约鲁巴语'=>'yor',
            '亚美尼亚语'=>'arm',   
            '伊努克提图特语'=>'iku',           
            '伊朗语'=>'ir',
            '中文(简体)'=>'zh',   
            '中文(繁体)'=>'cht',   
            '中文(文言文)'=>'wyw',
            '中文(粤语)'=>'yue',   
            '扎扎其语'=>'zaz',   
            '中古法语'=>'frm',
            '祖鲁语'=>'zul',   
            '爪哇语'=>'jav',
        ];
    }
}

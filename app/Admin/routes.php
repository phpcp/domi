<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    
    $router->get('/', 'HomeController@index')->name('home');

    //---------公共权限(使用用户都必须拥有该权限)
    //权限验证
    $router->post('prohibit', 'CommonController@prohibit')->name('prohibit');
    $router->post('modular-status', 'CommonController@modular_status')->name('modular_status');
    //语言设置
    $router->get('wlanguage-list', 'WlanguageController@wlanguage_list')->name('wlanguage_list');
    $router->get('wlanguage-ajax', 'WlanguageController@wlanguage_ajax')->name('wlanguage_ajax');
    $router->get('wlanguage-add', 'WlanguageController@wlanguage_add')->name('wlanguage_add');
    $router->post('wlanguage-baidu', 'WlanguageController@wlanguage_baidu')->name('wlanguage_baidu');
    $router->post('wlanguage-form', 'WlanguageController@wlanguage_form')->name('wlanguage_form');
    $router->post('wlanguage-show', 'WlanguageController@wlanguage_show')->name('wlanguage_show');
    //课程管理
    $router->get('wcourse-list', 'WcourseController@wcourse_list')->name('wcourse_list');
    $router->get('wcourse-ajax', 'WcourseController@wcourse_ajax')->name('wcourse_ajax');
    $router->get('wcourse-add', 'WcourseController@wcourse_add')->name('wcourse_add');
    $router->post('wcourse-form', 'WcourseController@wcourse_form')->name('wcourse_form');
    $router->get('wcourse-lang-list', 'WcourseController@wcourse_lang_list')->name('wcourse_lang_list');
    $router->get('wcourse-lang-ajax', 'WcourseController@wcourse_lang_ajax')->name('wcourse_lang_ajax');
    $router->get('wcourse-screen', 'WcourseController@wcourse_screen')->name('wcourse_screen');
    
    //子课程表
    $router->get('wcourse-low-list', 'WcourseLowController@wcourse_low_list')->name('wcourse_low_list');
    $router->get('wcourse-low-ajax', 'WcourseLowController@wcourse_low_ajax')->name('wcourse_low_ajax');
    $router->get('wcourse-low-add', 'WcourseLowController@wcourse_low_add')->name('wcourse_low_add');
    $router->post('wcourse-low-form', 'WcourseLowController@wcourse_low_form')->name('wcourse_low_form');
    $router->post('wcourse-low-video', 'WcourseLowController@wcourse_low_video')->name('wcourse_low_video');
    $router->get('wcourse-low-lang-list', 'WcourseLowController@wcourse_low_lang_list')->name('wcourse_low_lang_list');
    $router->get('wcourse-low-lang-ajax', 'WcourseLowController@wcourse_low_lang_ajax')->name('wcourse_low_lang_ajax');
    //用户管理
    $router->get('wanchor-user-list', 'WanchorUserController@wanchor_user_list')->name('wanchor_user_list');
    $router->get('wanchor-user-ajax', 'WanchorUserController@wanchor_user_ajax')->name('wanchor_user_ajax');
    $router->get('wanchor-user-add', 'WanchorUserController@wanchor_user_add')->name('wanchor_user_add');
    $router->post('wanchor-user-form', 'WanchorUserController@wanchor_user_form')->name('wanchor_user_form');
    $router->post('wanchor-user-show', 'WanchorUserController@wanchor_user_show')->name('wanchor_user_show');
    $router->get('wanchor-user-screen', 'WanchorUserController@wanchor_user_screen')->name('wanchor_user_screen');
    //国家管理
    $router->get('wcountryz-list', 'WcountryzController@wcountryz_list')->name('wcountryz_list');
    $router->get('wcountryz-ajax', 'WcountryzController@wcountryz_ajax')->name('wcountryz_ajax');
    $router->get('wcountryz-add', 'WcountryzController@wcountryz_add')->name('wcountryz_add');
    $router->post('wcountryz-form', 'WcountryzController@wcountryz_form')->name('wcountryz_form');
    $router->post('wcountryz-show', 'WcountryzController@wcountryz_show')->name('wcountryz_show');
    $router->post('wcountryz-iso', 'WcountryzController@wlanguage_iso')->name('wlanguage_iso');
    //工会管理 
    $router->get('wmeeting-list', 'WmeetingController@wmeeting_list')->name('wmeeting_list');
    $router->get('wmeeting-ajax', 'WmeetingController@wmeeting_ajax')->name('wmeeting_ajax');
    $router->get('wmeeting-add', 'WmeetingController@wmeeting_add')->name('wmeeting_add');
    $router->post('wmeeting-form', 'WmeetingController@wmeeting_form')->name('wmeeting_form');
    $router->post('wmeeting-show', 'WmeetingController@wmeeting_show')->name('wmeeting_show');
    //代理管理  
    $router->get('wagent-list', 'WagentController@wagent_list')->name('wagent_list');
    $router->get('wagent-ajax', 'WagentController@wagent_ajax')->name('wagent_ajax');
    $router->get('wagent-add', 'WagentController@wagent_add')->name('wagent_add');
    $router->post('wagent-form', 'WagentController@wagent_form')->name('wagent_form');
    $router->post('wagent-show', 'WagentController@wagent_show')->name('wagent_show');
    //基础设置
    $router->get('wconfig-list', 'WconfigController@wconfig_list')->name('wconfig_list');
    $router->get('wconfig-ajax', 'WconfigController@wconfig_ajax')->name('wconfig_ajax');
    $router->get('wconfig-add', 'WconfigController@wconfig_add')->name('wconfig_add');
    $router->post('wconfig-form', 'WconfigController@wconfig_form')->name('wconfig_form');
    //任务管理
    $router->get('wtask-list', 'WtaskController@wtask_list')->name('wtask_list');
    $router->get('wtask-ajax', 'WtaskController@wtask_ajax')->name('wtask_ajax');
    $router->get('wtask-add', 'WtaskController@wtask_add')->name('wtask_add');
    $router->post('wtask-form', 'WtaskController@wtask_form')->name('wtask_form');
    //页面语言
    $router->get('wfront-page-list', 'WfrontPageController@wfront_page_list')->name('wfront_page_list');
    $router->get('wfront-page-ajax', 'WfrontPageController@wfront_page_ajax')->name('wfront_page_ajax');
    $router->get('wfront-page-screen', 'WfrontPageController@wfront_page_screen')->name('wfront_page_screen');
    $router->get('wfront-page-add', 'WfrontPageController@wfront_page_add')->name('wfront_page_add');
    $router->post('wfront-page-form', 'WfrontPageController@wfront_page_form')->name('wfront_page_form');

    //页面字段
    $router->get('page-field-list', 'WfrontPageController@page_field_list')->name('page_field_list');
    $router->get('page-field-ajax', 'WfrontPageController@page_field_ajax')->name('page_field_ajax');
    $router->get('page-field-add', 'WfrontPageController@page_field_add')->name('page_field_add');
    $router->post('page-field-form', 'WfrontPageController@page_field_form')->name('page_field_form');
    $router->get('page-field-screen', 'WfrontPageController@page_field_screen')->name('page_field_screen');
    //语言翻译
    $router->get('translate-list', 'WfrontPageController@translate_list')->name('translate_list');
    $router->get('translate-ajax', 'WfrontPageController@translate_ajax')->name('translate_ajax');
    $router->post('translate-form', 'WfrontPageController@translate_form')->name('translate_form');
    $router->post('translate-translate', 'WfrontPageController@translate_translate')->name('translate_translate');
    $router->get('translate-add', 'WfrontPageController@translate_add')->name('translate_add');
});
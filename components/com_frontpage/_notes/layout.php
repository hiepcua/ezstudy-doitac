<link href="<?php echo ROOTHOST.THIS_TEM_PATH; ?>css/swiper.css" type="text/css" rel="stylesheet" media="all">
<script src='<?php echo ROOTHOST.THIS_TEM_PATH;?>js/swiper.min.js'></script>
<div id='color-block'>
    <div class='container clearfix'>
        <hgroup>
            <h1>Chia sẻ kiến thức là sứ mệnh của chúng tôi</h1>
            <h2>Nơi hội tụ kiến thức cộng đồng</h2>
        </hgroup>
        <div class="blocks">
            <?php
            include_once(LIB_PATH.'cls.lession.php');
            include_once(LIB_PATH.'cls.course.php');
            include_once(LIB_PATH.'cls.subject.php');
            $objLess=new CLS_LESSION();
            $objCour=new CLS_COURSE();
            $objSub=new CLS_SUBJECT();
            $strWh='WHERE `isactive`=1';
            $count_subject=$objSub->countSubject($strWh);
            $objSub->getList("WHERE `isactive`=1 LIMIT 0,9");
            $stt='';
            while($rows=$objSub->Fetch_Assoc())
            {	$stt++;
                $id=$rows["id"];
                $code=$rows["code"];
                $name=Substring($rows["name"], 0 , 10);
                switch($stt){
                    case '1': {
                        $class="army";
                        $fa="fa-code";
                    }; break;
                    case '2': {
                        $class="orange";
                        $fa="fa-laptop";
                    }; break;
                    case '3': {
                        $class="teal";
                        $fa="fa-globe";
                    }; break;
                    case '4': {
                        $class="plum";
                        $fa="fa-camera";
                    }; break;
                    case '5': {
                        $class="purple";
                        $fa="fa-pie-chart";
                    }; break;
                    case '6': {
                        $class="yellow";
                        $fa="fa-graduation-cap";
                    }; break;
                    case '7': {
                        $class="green";
                        $fa="fa-codepen";
                    }; break;
                    case '8': {
                        $class="blue";
                        $fa="fa-video-camera";
                    }; break;
                    case '9': {
                        $class="sky";
                        $fa="fa-music";
                    }; break;
                    default: $class="sky";
                }
                $url=ROOTHOST.$code;
                $strWh="WHERE sub_id='$id'";
                $count_lession=$objLess->countLession($strWh);
                $count_cour=$objCour->countCourse($strWh);
                ?>
                    <div  class="subject_toggle flip-container <?php echo $class;?>" ontouchstart="this.classList.toggle('hover');">
                        <a href="<?php echo $url;?>" class="flipper">
                            <div class="front"><h3><?php echo $name;?></h3><i class="fa <?php echo $fa;?>"></i></div>
                            <div class="back"><strong><?php echo $count_cour;?></strong><span>Khóa học</span><strong><?php echo $count_lession;?></strong><span>Bài giảng</span></div>
                            <!--<div class="back"><strong>371</strong><span>COURSES</span><strong>15,742</strong><span>VIDEO TUTORIALS</span></div>-->
                        </a>
                    </div>
            <?php }?>
            <div class="container-b">
                <a href="" class="em-button yellow larger" data-qa="qa_join_now"><div class="black"><center><span>Đăng ký học ngay</span><br><i class="fa fa-thumbs-o-up"></i></center></div></a>
            </div>
        </div>
    </div>
</div>
<div class='frontpage'>
<div class="block-choose clearfix margin-bottom-40"><div class="container">
        <hgroup>
            <h2><strong class='bg-orange'>Openlearn</strong> có thể giúp gì cho bạn</h2><br>
        </hgroup>
        <ul class="feature row">
            <?php
            include_once(LIB_PATH.'cls.content.php');
            $objCon=new CLS_CONTENTS();
            $objCon->getListHome('', 'LIMIT 0,6');
            ?>
        </ul>
    </div>
</div>
<div class='block-team clearfix'><div class="container">
        <hgroup>
            <h2><strong class='bg-orange'>Giảng viên</strong> là những chuyên gia hàng đầu trong lĩnh vực</h2><br>

        </hgroup>
        <div class="row">
           <?php
           $objmem=new CLS_MEMBER();
           $objmem->getListTeacher('', 'LIMIT 0,3');
           ?>
        </div>
    </div></div>
<!--<div class='block-price clearfix'><div class='container'>
        <h2 class='margin-bottom-50'>Bảng giá khóa học tại <strong class='bg-orange'>Openlearn</strong></h2>
        <div class='row'>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="pricing-item">
                    <div class="pricing-head">
                        <h3>Beginner</h3>
                    </div>
                    <div class="pricing-content">
                        <div class="pi-price">
                            <p><strong>19đ</strong> /tháng</p>
                        </div>
                        <ul class="list-unstyled">
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li style="border:none"><p>Access to all course</p> <strong>Ulimited</strong></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="pricing-footer">
                        <a class="btn btn-default" >Sign Up</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="pricing-item">
                    <div class="pricing-head">
                        <h3>Beginner</h3>
                    </div>
                    <div class="pricing-content">
                        <div class="pi-price">
                            <p><strong>19đ</strong> /tháng</p>
                        </div>
                        <ul class="list-unstyled">
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li style="border:none"><p>Access to all course</p> <strong>Ulimited</strong></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="pricing-footer">
                        <a class="btn btn-default" >Sign Up</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="pricing-item">
                    <div class="pricing-head">
                        <h3>Beginner</h3>
                    </div>
                    <div class="pricing-content">
                        <div class="pi-price">
                            <p><strong>19đ</strong> /tháng</p>
                        </div>
                        <ul class="list-unstyled">
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li style="border:none"><p>Access to all course</p> <strong>Ulimited</strong></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="pricing-footer">
                        <a class="btn btn-default" >Sign Up</a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="pricing-item">
                    <div class="pricing-head">
                        <h3>Beginner</h3>
                    </div>
                    <div class="pricing-content">
                        <div class="pi-price">
                            <p><strong>19đ</strong> /tháng</p>
                        </div>
                        <ul class="list-unstyled">
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li><p>Access to all course</p> <strong>Ulimited</strong></li>
                            <li style="border:none"><p>Access to all course</p> <strong>Ulimited</strong></li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="pricing-footer">
                        <a class="btn btn-default" >Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div></div>-->
<link href="<?php echo ROOTHOST.THIS_TEM_PATH; ?>css/swiper.min.css" type="text/css" rel="stylesheet" media="all">
<div class='block-price clearfix'>
    <div class='container'>
        <h2>Khóa học tiêu biểu tại <strong class='bg-orange'>Openlearn</strong></h2>
        <div id="slider-main" class="swiper-container slider-item list-box">
            <div class="swiper-wrapper">
                    <?php $objCour->getListItemSliderHome(); ?>
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next btn-next"></div>
            <div class="swiper-button-prev btn-prev"></div>
        </div>
    </div>
</div>

<div class='block-testimonials clearfix'>
    <div class='container'>
        <hgroup>
            <h2>Cảm nhận học viên tại <strong class='bg-orange'>Openlearn</strong></h2>
        </hgroup>
        <div id="slider-feedback" class="swiper-container slider-item list-box">
            <div class="swiper-wrapper">
                <div class="row">
                    <?php
                    include_once(LIB_PATH."cls.feedback.php");
                    $objFeed=new CLS_FEEDBACK();
                    $objFeed->getListItemSlider('', 'LIMIT 0,3');
                    ?>
                </div>

            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class='pre-footer clearfix'>
    <div class='container'>
        <div class="row">
            <div class="col-md-4 col-sm-6 pre-footer-col">
                <h3>Openlearn</h3>
                <p>Học từ Học bài giảng từ những con người đam mê chia sẻ kiến thức.</p>
                <p> Đặc điểm của nền kinh tế này là dịch vụ, một lĩnh vực đã và đang thu hút nhiều lao động tham gia nhất, đặc biệt là những lao động có tri thức cao. Do đó việc nâng cao hiệu quả chất lượng giáo dục, đào tạo sẽ là nhân tố sống còn quyết định sự tồn tại và phát triển của mỗi quốc gia, công ty, gia đình và cá nhân</p>
            </div>
            <div class="col-md-4 col-sm-6 pre-footer-col">
                <h3>Dành cho bạn</h3>
                <a href="#" title="" class="link">Nâng cấp tài khoản</a>
                <a href="#" title="" class="link">Tìm kiếm nâng cao</a>
                <a href="#" title="" class="link">Đăng ký làm giảng viên</a>
            </div>
            <div class="col-md-4 col-sm-6 pre-footer-col">
                <!-- BEGIN BOTTOM CONTACTS -->
                <h3>Liên Hệ</h3>
                <address class="margin-bottom-20">
                    Số 6 - Ngõ 30/28 - Đường Tăng Thiết Giáp - Phường Cổ Nhuế - Quận Bắc Từ Liêm,
                    Hà Nội - Việt Nam<br>
                    Phone: <a href="tel:0432121015">0432.12.10.15</a><br>
                    Fax: 0432.12.10.15<br>
                    Email: <a href="mailto:nxtuyen.pro@gmail.com">nxtuyen.pro@gmail.com</a><br>
                    Skype: <a href="skype:nxtuyen.pro">nxtuyen.pro</a>
                </address>
                <!-- END BOTTOM CONTACTS -->

                <div class="pre-footer-subscribe-box">
                    <h2>Nhận Thông Tin Mới</h2>
                    <form action="javascript:void(0);">
                        <div class="input-group">
                            <input type="text" placeholder="youremail@mail.com" class="form-control">
					<span class="input-group-btn">
					  <button class="btn btn-primary" type="submit">Đăng Ký</button>
					</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function(){
        /*slider*/
        var elem_main = document.getElementById('slider-main');
        var swiper = new Swiper(elem_main, {
            pagination: '.swiper-pagination',
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            paginationClickable: true,
            spaceBetween: 0,
            centeredSlides: true,
            speed: 600,
            autoplay: 4000,
            loop: true,
            autoplayDisableOnInteraction: false
            /*onSlideChangeStart: function (s) {
             var activeSlideHeight = s.slides.eq(s.activeIndex).height();
             $(elem_main).css({height: activeSlideHeight+'px'});
             }	*/
        });
        /*slider*/

        var elem_slider = document.getElementById('slider-feedback');
        var swiper = new Swiper(elem_slider, {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            spaceBetween: 0,
            centeredSlides: true,
            speed: 600,
            autoplay: 4000,
            loop: true,
            autoplayDisableOnInteraction: false
            /*onSlideChangeStart: function (s) {
             var activeSlideHeight = s.slides.eq(s.activeIndex).height();
             $(elem_main).css({height: activeSlideHeight+'px'});
             }	*/
        });
    })

</script>

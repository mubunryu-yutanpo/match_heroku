<template>
    <div class="p-home">
        <!-- hero -->
        <section class="p-hero">
            <img src="images/hero.png" class="p-hero__image">
            <img src="images/hero_sp.png" class="p-hero__image--sp">
        </section>
        
        <!-- こんなお悩みありませんか的な部分 -->
        <section class="p-catch">
            <p class="p-catch__title">もっと簡単に</p>
            <p class="p-catch__title--sub">PROBLEM</p>
            <div class="p-catch__container">
                <p class="p-catch__text">
                    <i class="fa-solid fa-check c-icon"></i>
                    あんなことやこんなこと
                </p>
            </div>
            <div class="p-catch__container">
                <p class="p-catch__text">
                    <i class="fa-solid fa-check c-icon"></i>
                    色々あったけどもさ。
                </p>
            </div>
            <div class="p-catch__container">
                <p class="p-catch__text">
                    <i class="fa-solid fa-check c-icon"></i>
                    今日まで、ここまで来れたのはみんなのおかげです。
                </p>
            </div>
        </section>
        
        <!-- 説明の部分 -->
        <section class="p-about">
            <p class="p-about__title">技術の「欲しい」を手軽にやり取り</p>
            <p class="p-about__title--sub">ABOUT</p>
        </section>
        
        <!-- 実績的な部分 -->
        <section class="p-case">
            <h2 class="p-case__title">案件の一例</h2>
            <strong class="p-case__title-sub">PROJECTS</strong>


            <div class="p-case__container">
            <!-- スライダー -->
            <swiper :options="swiperOptions">
                <swiper-slide class="" v-for="project in projectList" :key="project.id" style="height:auto;">
                <div class="p-project u-width--100">
                    <h3 class="p-project__title c-title">{{ project.title }}</h3>
                    <div class="p-project__image">
                        <img :src="project.thumbnail" class="p-project__image-item c-image">
                        <p class="p-project__type c-text--type">{{ project.type.name }}</p>
                    </div>
                    <p class="p-project__content c-text">{{ project.content}}</p>
                </div>
                </swiper-slide>
            </swiper>

            </div>

            <div class="">
                <button class="">
                    <a href="/list" class="">すべての案件を見る</a>
                </button>
            </div>

        </section>
        
        <!-- クロージング -->
        <section class="p-action">
            <img src="images/action.png" class="p-action__image">
            <img src="images/action_sp.png" class="p-action__image--sp">
            <button class="p-action__button c-button">案件に応募する！</button>
            <p class="p-action__text">無料の会員登録が必要です</p>
        </section>
    </div>
</template>

<script>
import axios from 'axios';


export default {
    
    data() {
        return {
        projectList: [],

        // swiperの設定たち
        swiperOptions: {
            loop: true, // ループ有効
            //speed: 6000, // ループの時間
            autoplay: {
            delay: 3000, // 途切れなくループ
            },        
            spaceBetween: 45, // 余白
            slidesPerView: 1,// 一度に表示する枚数
            
            breakpoints: {
            
            420:{
                slidesPerView: 2,
                spaceBetween: 15
            },

            768:{
                slidesPerView: 3
            }
            },
        },

        }
    },

    methods: {

        // アイデア情報の取得
        async getprojects() {
        try {
            const response = await axios.get('/api/top/projects');
            this.projectList = response.data.projectList;
            console.log(response.data)
        } 
        catch (error) {
            console.log(error);
        }
        },


    },


    filters: {
        
        // 値段の単位をカンマ区切りにする
        numberWithCommas(value) {
        if (value ===0) {
            return '0';
        }
        if (!value) {
            return '';
        }
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
    },

    mounted() {
        // APIからアイデアデータを取得
        this.getprojects();

        },

    };
</script>

<style>
    .swiper-container{
        border-radius: 5px;
    }
    .swiper-slide{
        max-width: 350px;
    }
</style>


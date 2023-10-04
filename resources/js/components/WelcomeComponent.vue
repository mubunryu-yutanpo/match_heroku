<template>
    <div class="p-home">
        <!-- hero -->
        <section class="p-hero"></section>
        <!-- こんなお悩みありませんか的な部分 -->
        <section class="p-catch"></section>
        <!-- 説明の部分 -->
        <section class="p-about"></section>
        <!-- 実績的な部分 -->
        <section class="p-case"></section>
        <!-- クロージング -->
        <section class="p-action">
            <img src="images/action.png" class="p-action__image">
            <img src="images/action_sp.png" class="p-action__image--sp">  

            <button class="p-action__button c-button">無料で登録する！</button>
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
        async getIdeas() {
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
        this.getIdeas();

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


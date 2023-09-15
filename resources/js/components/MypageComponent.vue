<template>
    <div class=""><!-- 全体のwrap -->
        
        <!-- ユーザー情報 -->
        <section class="p-user c-box--user">
            <p class="p-user__name c-text">{{ this.user.name }}</p>
            <div class="p-user__image c-image-box">
                <img :src="user.avatar" alt="" class="p-user__item c-image">
            </div>
        </section>

        <!-- 各リスト -->
        <section class="">

            <!-- 投稿案件一覧 -->
            <div class="p-list">
                <h3 class="c-title p-list__title">投稿した案件一覧</h3>
                <div class="p-list__container" v-if="postList.length > 0" >

                    <div class="p-card" v-for="post in postList" :key="post.id">
                        <a :href="'/project/' + post.id + '/detail'" class="c-link p-card__link">{{ post.title }}</a>
                    
                        <div class="c-image-box p-card__image-box">
                            <p class="c-type">{{ post.type.name }}</p>
                            <img :src="post.thumbnail" alt="" class="c-image p-card__image-item">
                        </div>

                        <p class="p-list__content">{{ post.content }}</p>
                    </div>
                    <a :href="'/postList/'+ this.user.id" class="c-link p-list__link" v-if="postList.length > 5">全件表示する</a>

                </div>
                <div v-else>
                    <strong>投稿した案件はまだありません</strong>
                </div>
            </div>

            <!-- 応募案件一覧 -->
            <div class="p-list">
                <h3 class="c-title p-list__title">応募した案件一覧</h3>
                <div class="p-list__container" v-if="applyList.length > 0" >

                    <div class="p-card" v-for="apply in applyList" :key="apply.id">
                        <a :href="'/project/' + apply.id + '/detail'" class="c-link p-card__link">{{ apply.title }}</a>
                    
                        <div class="c-image-box p-card__image-box">
                            <p class="c-type">{{ apply.type.name }}</p>
                            <img :src="apply.thumbnail" alt="" class="c-image p-card__image-item">
                        </div>

                        <p class="p-list__content">{{ apply.content }}</p>
                    </div>
                    <a :href="'/applyList/'+ this.user.id" class="c-link p-list__link" v-if="applyList.length > 5 " >全件表示する</a>

                </div>
                <div v-else>
                    <strong>応募した案件はまだありません</strong>
                </div>
            </div>

            <!-- パブリックM一覧 -->
            <div class="p-list">
                <h3 class="c-title p-list__title">パブリックメッセージ一覧</h3>
                <div class="p-list__container" v-if="publicMessageList.length > 0" >

                    <div class="p-card" v-for="pubMessage in publicMessageList" :key="pubMessage.id">
                        <a :href="'/project/' + pubMessage.project.id + '/detail'" class="c-link p-card__link">{{ apply.title }}</a>
                    
                        <div class="c-image-box p-card__image-box">
                            <p class="c-type">{{ pubMessage.project.type.name }}</p>
                            <img :src="pubMessage.project.thumbnail" alt="" class="c-image p-card__image-item">
                        </div>

                        <p class="p-list__content">{{ pubMessage.project.content }}</p>
                    </div>
                    <a :href="'/publicMessageList/'+ this.user.id" class="c-link p-list__link"  v-if="publicMessageList.length > 5">全件表示する</a>

                </div>
                <div v-else>
                    <strong>メッセージはまだありません</strong>
                </div>
            </div>

            <!-- DM一覧 -->
            <div class="p-list">
                <h3 class="c-title p-list__title">ダイレクトメッセージ一覧</h3>
                <div class="p-list__container" v-if="directMessageList.length > 0" >

                    <div class="p-card" v-for=" dm in directMessageList" :key="dm.id">
                        <!-- 各チャットの最新の1件のみ表示 -->
                        <div class="p-user c-box--user c-box--listUser">
                            <div class="p-user__image c-image-box c-image-box--forList ">
                                <img :src="dm.message[0].user.avatar" class="p-user__item c-image">
                            </div>
                            <p class="p-user__name">{{ dm.message[0].user.name }}</p>
                        </div>

                        <p class="p-list__content">{{ dm.message[0].comment }}</p>
                        <a :href="'/messages/' + dm.user1_id + dm.user2_id " class="c-link p-list__link">このメッセージへ</a>
                    </div>
                    <a :href="'/directMessageList/'+ this.user.id" class="c-link p-list__link" v-if="directMessageList.length > 5">全件表示する</a>

                </div>
                <div v-else>
                    <strong>メッセージはまだありません</strong>
                </div>
            </div>

        </section>

    </div>
</template>

<script>
import axios from 'axios';

export default {

    props: ['user'],

    data() {
        return {
            postList : [],
            applyList: [],
            publicMessageList: [],
            directMessageList: [],
        };
    },

    async mounted() {
        await this.getMypage(); // ページ読み込み時にマイページ情報を取得
    },

    methods: {

        // 案件詳細情報取得
        getMypage(){
            axios.get('/api/' + this.user.id + '/mypage').
            then((response) => {
                this.postList  = response.data.postList;
                this.applyList = response.data.applyList;
                this.publicMessageList = response.data.publicMessageList;
                this.directMessageList = response.data.directMessageList;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        // 日付の表示を変更
        formatDate(value) {
            const date = new Date(value);
            const year = date.getFullYear();
            const month = date.getMonth() + 1;
            const day = date.getDate();
            return `${year}.${month}.${day}`;
        },
    },

    filters: {
        // 値段の表記。コンマ区切りにする。
        numberWithCommas(value) {
            if (value === 0) {
                return '0';
            }
            if (!value) {
                return '';
            }
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },
    },


}
</script>

<style>
    .c-box--user{
        align-items: center;
        display: flex;
        justify-content: flex-end;
        padding-right: 20px;
    }
    .c-box--listUser{
        justify-content: flex-start;
    }
    .c-image-box{
        box-shadow: 0 0 8px 0 gray;
        border-radius: 5px 0 0 0;
        max-width: 30%;
        height: 220px;
    }
    .c-image-box--forList{
        margin-left: 0;
        margin-right: 10px;
    }
    .c-image{
        height: 100%;
        width:  100%;
    }

    .p-user__image{
        border: 1px solid white;
        border-radius: 100%;
        height: 40px;
        margin-left: 10px;
        padding: 1px;
        width: 40px;
    }
    .p-user__item{
        border-radius: 100%;
    }
</style>
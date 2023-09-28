<template>
    <div class="p-user-info c-box">
                    
        <div class="p-user-info__name c-box--flex c-box--flex-3">
            <div class="c-box--avatar">
                <img :src="user.avatar" class="c-image">
            </div>
            {{ user.name }} さん
        </div>

        <div class="p-user-info__container">

            <!-- ユーザー情報 -->
            <div class="p-user-info__count c-box--flex c-box--flex-4">
                <div class="p-user-info__count--post">
                    <p class="c-text">投稿した案件数</p>
                    <p class="p-user-info__count-text c-text">{{ post_count }}</p>
                </div>

                <div class="p-user-info__count--apply">
                    <p class="c-text">応募した案件数</p>
                    <p class="p-user-info__count-text c-text">{{ apply_count }}</p>
                </div>
            </div>

            <!-- 自己紹介文 -->
            <div class="p-user-info__introduction">
                <p class="c-text">自己紹介文:</p>
                <p class="p-user-info__introduction-text c-text" v-if="user.introduction">{{ user.introduction }}</p>
                <p class="p-user-info__introduction-text c-text" v-else>よろしくお願いします。</p>
            </div>

            <!-- DM用リンク -->
            <div class="p-user-info__message c-box--link" v-if="this.user.id !== this.auth_user_id">
                <a :href="'/messages/' + this.auth_user_id + '/' + this.user.id" class="p-user-info__message-link c-link">{{ user.name }}さんにメッセージを送る</a>
            </div>


        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {

    props: [
        'user',
        'auth_user_id', // Authユーザー
        'post_count',
        'apply_count'
    ],

    data() {
        return {
            project : [],
        };
    },

    async mounted() {
        await this.getDetail(); // ページ読み込み時に案件情報を取得
    },

    methods: {

        // 案件詳細情報取得
        getDetail(){
            axios.get('/api/' + this.project_id + '/detail').
            then((response) => {
                this.project = response.data.project;
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

}
</script>
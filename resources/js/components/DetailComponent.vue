<template>
    <div class=""><!-- 全体のwrap -->
        
        <div class=""><!-- タイトル・DMとか？ -->
            <p class="">{{ project.type ? project.type.name : '未指定' }}</p>
            <h2 class="">{{ project.title }}</h2>
            
            <div class="">
                <a :href="'/messages/' + this.user_id + '/' + this.project.user_id " class="" v-if="this.user_id !== this.project.user_id">
                    メッセージを送る
                </a>
                <p>X(旧Twitter)にシェアする</p>
            </div>

        </div>

        <div class=""><!-- 詳細のwrap -->
            <p class="">{{ project.content }}</p>
            <p>
                料金： {{ project.lowerPrice | numberWithCommas }}〜{{ project.upperPrice | numberWithCommas }} 円
            </p>
        </div>

        <!-- メッセ部分 -->
        <public-message-component :project_id="project_id" :user_id="user_id"></public-message-component>

    </div>
</template>

<script>
import axios from 'axios';

export default {

    props: [
        'project_id',
        'user_id', // Authユーザー
    ],

    data() {
        return {
            project : [],
            // messageList : [],
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
                // this.messageList = response.data.messageList;
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
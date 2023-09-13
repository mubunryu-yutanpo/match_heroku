<template>
    <div class=""><!-- 全体のwrap -->
        <img :src="this.user.avatar" alt="" class="">
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
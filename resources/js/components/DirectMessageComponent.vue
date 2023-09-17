<template>
    <div class=""><!-- 全体のwrap -->

        <!-- フラッシュメッセージを表示 -->
        <div v-if="flashMessage" :class="flashMessageType">{{ flashMessage }}</div>

        <!-- メッセージを表示するエリア -->
        <div class="">
            <div class="" v-for="message in messageList" :key="message.id" :class="{'me': 1 === message.sender_id , 'you': 1 !== message.sender_id}">
                <!-- ユーザー -->
                <div class="">
                    DM
                    <img :src="message.user.avatar" alt="" class="">
                    <p class="">{{ message.user.name }}</p>
                </div>
                <!-- メッセージ -->
                <div class="">
                    <p class="">{{ message.comment }}</p>
                </div>
            </div>
        </div>
        <!-- メッセージ入力＆送信エリア -->
        <form @submit.prevent="addMessage" class="">
            <div class="">
                
                <textarea
                    class=""
                    :class="{'c-error': countText > max }"
                    v-model="newMessage"
                    placeholder="メッセージを送信"
                    @input="updateCount"
                ></textarea>

                <button type="submit" class="">
                    <i class=" fa-solid fa-paper-plane"></i>
                </button>
            </div>
            <span class="" :class="{'c-error c-error--text': countText > max }"> {{ countText }} / {{ max }}</span>
        </form>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: [
        'user',
        'chat',
    ],

    data(){
        return{
            messageList : [],
            newMessage: '',
            flashMessage: '',
            flashMessageType: '',
            max: 255,
            countText: 0, // 文字数カウントを初期化
        };
    },

    async mounted() {
        await this.getMessages(); // ページ読み込み時に案件情報を取得
    },

    methods: {

        // メッセージ情報取得
        getMessages(){
            axios.get('/api/messages/' + this.chat.id ).
            then((response) => {
                this.messageList = response.data.messageList;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        // メッセージ追加
        addMessage() {
            axios
            .post('/api/message/' + this.user.id + '/' + this.chat.id , { comment: this.newMessage })
            .then((response) => {
                this.flashMessage = response.data.flashMessage;
                this.flashMessageType = response.data.flashMessageType;
                this.getMessages(); // メッセージの送信後にメッセージを再取得
                this.newMessage = ''; // 送信後、入力欄をクリア
                this.countText = 0;// カウンターをリセット
            })
            .catch((error) => {
                console.error(error);
            });
        },

        // 入力文字数をカウント
        updateCount() {
            this.countText = this.newMessage.length;
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
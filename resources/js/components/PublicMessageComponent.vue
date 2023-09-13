<template>
    <div class="">
        <!-- コメント表示/非表示ボタン -->
        <button class="" @click="toggleShowComment()">
            <span class="" v-if="!showComment">コメントを表示</span>
            <span class="" v-if="showComment">コメントを非表示</span>
        </button>

        <!-- 全体のwrap -->
        <div class="" v-if="showComment">
            
            <!-- メッセージを表示するエリア -->
            <div class="">
                <div class="" v-for="message in messageList" :key="message.id" :class="{'seller': seller_id === message.user.id , 'other': seller_id !== message.user.id}">
                    <!-- ユーザー -->
                    <div class="">
                        アロハ
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
                    <p class="">この案件に対する質問などをコメントできます（※255文字以内）</p>
                    <textarea class="" v-model="newMessage" placeholder="メッセージを送信"></textarea>
                    <button type="submit" class="">
                        <i class=" fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>

</template>

<script>
import axios from 'axios';

export default {
    props: [
        'project_id',
        'user_id',
    ],

    data(){
        return{
            messageList : [],
            seller_id: null,
            newMessage: '',
            showComment : true,
        };
    },

    async mounted() {
        await this.getMessages(); // ページ読み込み時に案件情報を取得
    },

    methods: {

        // メッセージ情報取得
        getMessages(){
            console.log(this.pro);
            axios.get('/api/' + this.project_id + '/publicMessages').
            then((response) => {
                this.messageList = response.data.messageList;
                this.seller_id = response.data.seller_id;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        // メッセージ追加
        addMessage() {
            axios
            .post('/api/project/' + this.project_id + '/' + this.user_id + '/publicMessage', { comment: this.newMessage })
            .then((response) => {
                // メッセージの送信後にメッセージを再取得する
                this.getMessages();
                this.newMessage = ''; // 送信後、入力欄をクリアする
            })
            .catch((error) => {
                console.error(error);
            });
        },

        // コメント表示切り替え
        toggleShowComment(){
            this.showComment = !this.showComment;
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
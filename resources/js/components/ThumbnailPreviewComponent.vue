<template>
    <div class="hoge__wrap u-align__stretch">
        <label for="thumbnail" class="hoge__label">サムネイル:</label>
        <div
            class="hoge__file-label"
            :class="{ 'preview': previewImage, 'dragover': isDragover }"
            @dragover.prevent="handleDragover"
            @dragleave="handleDragleave"
            @drop.prevent="handleDrop"
            @click="handleFileClick"
        >
            <!-- 8MBまで -->
            <input type="hidden" name="MAX_FILE_SIZE" value="8388608">
            <input type="file" class="hoge__file-input" name="thumbnail" ref="fileInput" @change="handleFileChange">
            <img :src="previewImage" alt="" class="hoge__file-image">
        </div>
        <span v-if="validError" class="hoge__error" role="alert">
            <strong>{{ validError }}</strong>
        </span>
    </div>
</template>

<script>
    import axios from 'axios';
    
    export default {

        props: ['project_id'],

        data() {
            return {
                thumbnailData: '',
                previewImage: null,
                validError: null,
                isDragover: false
            };
        },

        mounted() {

            // ユーザーデータを取得して、thumbnailを表示する
            axios.get('/api/' + this.project_id + '/thumbnail')
                .then(response => {
                this.thumbnailData = response.data.thumbnail;
                this.previewImage = this.thumbnailData;
            })
                .catch(error => {
                console.error(error);
            });
        },

        methods: {

            // 画像プレビュー
            handleFileChange() {
                const file = this.$refs.fileInput.files[0];
        
                // ファイル形式のバリデーション
                const allowedFormats = ['image/jpeg', 'image/png', 'image/gif', 'image/heic', 'image/heif'];
                if (!allowedFormats.includes(file.type)) {
                this.validError = '画像の形式が無効です。JPEG、PNG、GIF形式の画像を選択してください。';
                return;
                }
        
                // ファイルサイズのバリデーション
                const maxSizeInBytes = 8388608; // 8MB
                if (file.size > maxSizeInBytes) {
                this.validError = 'ファイルサイズが8MB以下の画像を選択してください。';
                return;
                }
        
                this.validError = null;
                this.previewImage = URL.createObjectURL(file);
            },

            // ドラッグ時
            handleDragover(event) {
                event.preventDefault();
                this.isDragover = true;
            },
            
            // ドロップ時
            handleDragleave() {
                this.isDragover = false;
            },

            // ドラッグ＆ドロップをした後のイベント
            handleDrop(event) {
                event.preventDefault();
                this.isDragover = false;
                
                // ドロップされたファイルをinput要素に
                this.$refs.fileInput.files = event.dataTransfer.files; 
                this.handleFileChange();
            },

            // クリック時にもイベントが起こるように
            handleFileClick() {
                this.$refs.fileInput.click();
            }
        },
    };
</script>

<style>
    .hoge__wrap{
        margin: 0 auto;
        width: 100%;

    }


    .hoge__file-label {
            background: white;
            border: 1px dashed gray;
            border-radius: 5px;
            color: brown;
            display: block;
            height: 300px;
            line-height: 300px;
            width: 400px;
            margin: 0 auto;
            position: relative;
            text-align: center;

            /* @include mq(sm){
                height: 230px;
                line-height: 230px;
                width: 100%;
            } */
    }
    
    .hoge__file-label.preview {
        border: none;
        box-shadow: 0 0 5px 3px brown;

        /* @include mq(){
            box-shadow: 0 0 5px 0 $brown;
        } */
    }
    
    .hoge__file-label.dragover {
        border-color: #000;
        background-color: #f0f0f0;
        color: #000;
    }
    
    .hoge__file-input {
        width: 100%;
        height: 100%;
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
    }
    
    .hoge__file-image {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }

</style>
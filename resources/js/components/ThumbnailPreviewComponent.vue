<template>
    <div class="p-thumbnail c-box--form-container">
        <label for="thumbnail" class="p-thumbnail__label c-label">サムネイル:</label>
        <div
            class="p-thumbnail__container"
            :class="{ 'is-preview': previewImage, 'is-dragover': isDragover }"
            @dragover.prevent="handleDragover"
            @dragleave="handleDragleave"
            @drop.prevent="handleDrop"
            @click="handleFileClick"
        >
            <!-- 8MBまで -->
            <input type="hidden" name="MAX_FILE_SIZE" value="8388608">
            <input id="thumbnail" type="file" class="p-thumbnail__input" name="thumbnail" ref="fileInput" @change="handleFileChange">
            <img :src="previewImage" alt="" class="p-thumbnail__image c-image">
            タップ（クリック）で画像を挿入
        </div>
        <p v-if="validError" class="c-error--text" role="alert">
            {{ validError }}
        </p>
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

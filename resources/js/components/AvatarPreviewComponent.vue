<template>
    <div class="p-avatar c-box--form-container">
        <label for="avatar" class="p-avatar__label c-label">アバター画像:</label>
        <div
            class="p-avatar__container"
            :class="{ 'is-preview': previewImage, 'is-dragover': isDragover }"
            @dragover.prevent="handleDragover"
            @dragleave="handleDragleave"
            @drop.prevent="handleDrop"
            @click="handleFileClick"
        >
            <!-- 8MBまで -->
            <input type="hidden" name="MAX_FILE_SIZE" value="8388608">
            <input type="file" class="p-avatar__input" name="avatar" ref="fileInput" @change="handleFileChange">
            <img :src="previewImage" alt="" class="p-avatar__image c-image">
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

        props: ['user'],

        data() {
            return {
                avatarData: '',
                previewImage: null,
                validError: null,
                isDragover: false
            };
        },

        mounted() {

            // ユーザーデータを取得して、avatarを表示する
            axios.get('/api/' + this.user.id + '/avatar')
                .then(response => {
                this.avatarData = response.data.avatar;
                this.previewImage = this.avatarData;
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
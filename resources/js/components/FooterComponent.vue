<template>
    <div class="p-footer" :class="{ 'is-small-contents': !isSmallContents }">
        <p class="p-footer__text">Copyright © match All Rights Reserved</p>
    </div>
</template>

<script>
    export default {
        
        data() {
            return {
                isSmallContents: false
            };
        },


        mounted() {
            this.judgeContentHeight();
            window.addEventListener('resize', this.judgeContentHeight);
        },


        methods: {
    
            // ウィンドウの高さよりコンテンツの高さが小さい場合はクラス名を追加
            judgeContentHeight() {
                const windowHeight = window.innerHeight;
                const documentHeight = document.documentElement.offsetHeight;
                const footer = document.querySelector('.p-footer');
        
                if (documentHeight < windowHeight) {
                    this.isSmallContents = true;
                } else {
                    this.isSmallContents = false;
                }
            },

        },
    }
</script>

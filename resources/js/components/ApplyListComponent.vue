<template>
    <div class="">
        
        <!-- 一覧 -->
        <section class="">

            <!-- 各案件 -->
            <div class="p-card" v-for="list in lists" :key="list.id">
                <p class="">{{ list.project.title }}</p>
            </div>
        </section>

        <!-- ページネーション -->
        <section class="">
            <!-- 最初のページへ -->
            <div class="p-pagination">
            <button
                v-if="currentPage > 1"
                class="p-pagination__button"
                @click="changePage(1)"
            >
                ＜
            </button>

            <!-- 前のページボタン -->
            <button
                v-if="currentPage > 2"
                class="p-pagination__button"
                @click="changePage(currentPage - 1)"
            >
                prev
            </button>

            <!-- 動的に変化するページボタン -->
            <button
                v-for="pageNumber in visiblePageNumbers"
                :key="pageNumber"
                class="p-pagination__button"
                :class="{ active: currentPage === pageNumber }"
                @click="changePage(pageNumber)"
            >
                {{ pageNumber }}
            </button>

            <!-- 次のページボタン -->
            <button
                v-if="currentPage < totalPages - 1"
                class="p-pagination__button"
                @click="changePage(currentPage + 1)"
            >
                next
            </button>

            <!-- 最終ページ -->
            <button
                v-if="currentPage < totalPages"
                class="p-pagination__button"
                @click="changePage(totalPages)"
            >
                ＞
            </button>
            </div>

        </section>

    </div>
</template>

<script>
    import axios from 'axios';

    export default {
    props : ['user_id'],
    
    data() {
        return {
        lists: [],
        currentPage: 1,
        listsPerPage: 1,
        };
    },

    async mounted() {
        await this.getList(); // ページ読み込み時に案件情報を取得
    },

    computed: {

        // フィルタリングされた案件の長さとlistsPerPageを元に、総ページ数を計算する
        totalPages() {
        return Math.ceil(this.lists.length / this.listsPerPage);
        },

        // 現在のページの開始インデックスと終了インデックスを計算する
        startIndex() {
        return (this.currentPage - 1) * this.listsPerPage;
        },
        endIndex() {
        return this.startIndex + this.listsPerPage;
        },

        // 現在のページの案件を取得する
        paginatedlists() {
        return this.lists.slice(this.startIndex, this.endIndex);
        },


        // ページネーションで表示するページ番号のリストを取得
        visiblePageNumbers() {
        const totalPages = this.totalPages;
        const currentPage = this.currentPage;

        const maxVisiblePages = 3; // 表示する最大のページ数

        if (totalPages <= maxVisiblePages) {
            return Array.from({ length: totalPages }, (_, i) => i + 1);

        } else {
            // 真ん中のボタン
            const middlePage = Math.floor(maxVisiblePages / 2) + 1;

            if (currentPage <= middlePage) {
            return Array.from({ length: maxVisiblePages }, (_, i) => i + 1);
            } else if (currentPage >= totalPages - middlePage + 1) {
            return Array.from({ length: maxVisiblePages }, (_, i) => totalPages - maxVisiblePages + i + 1);
            } else {
            return Array.from({ length: maxVisiblePages }, (_, i) => currentPage - middlePage + i);
            }
        }
        },

    },
    methods: {

        // 案件情報取得
        getList() {
            axios
            .get('/api/' + this.user_id + '/applyList')
            .then((response) => {
                this.lists = response.data.applyList;
            })
            .catch((error) => {
                console.error(error);
            });
        },

        // // 「気になる」の状態をトグル
        // toggleCheck(id) {
        //   axios.post('/api/idea/' + id + '/toggleCheck')
        //     .then(response => {
        //       console.log('気になるのトグル処理成功');
        //       this.isChecked = !this.isChecked; // チェックボックスの状態を反転させる
        //       this.getlists();
        //     })
        //     .catch(error => {
        //       console.error(error);
        //     });
        // },

        // 日付の表示を変更
        formatDate(value) {
        const date = new Date(value);
        const year = date.getFullYear();
        const month = date.getMonth() + 1;
        const day = date.getDate();
        return `${year}.${month}.${day}`;
        },


        // ページネーションのページ変更を処理する
        changePage(pageNumber) {
        this.currentPage = pageNumber;
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
    };
</script>


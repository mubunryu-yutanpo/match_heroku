<template>
    <div class="p-list">
        <h2 class="p-list__title c-title">
            <i class="fa-solid fa-list c-icon c-icon--title"></i>
            案件一覧
        </h2>
        
        <!-- ソート -->
        <div class="p-filter">
            <label for="projectTypeFilter" class="p-filter__label c-label">絞り込み:</label>
            <select id="projectTypeFilter" v-model="selectedProjectType" @change="filterProjects" class="p-filter__select c-select">
                <option value="">すべて</option>
                <option value="1">単発案件</option>
                <option value="2">レベニューシェア案件</option>
            </select>
        </div>

        <!-- 一覧 -->
        <section class="p-list__container c-box--flex">

            <!-- 各案件 -->
            <div class="p-project" v-for="project in paginatedProjects" :key="project.id">
                <h4 class="p-project__title c-title">
                    <a :href="'/project/' + project.id + '/detail'" class="p-project__link c-link">{{ project.title }}</a>
                </h4>
                <div class="p-project__image">
                    <img :src="project.thumbnail" class="p-project__image-item c-image">
                    <p class="p-project__type c-text--type">{{ project.type.name }}</p>
                </div>
                <div class="p-project__price">
                    <p class="c-text">【 料金 】 </p>
                    <p class="p-project__price-text c-text--price" v-if="project.lowerPrice && project.upperPrice">
                        {{ project.lowerPrice | numberWithCommas }}〜{{ project.upperPrice | numberWithCommas }} 
                    </p>
                    <p class="p-project__price-text c-text--price" v-else>ー</p>
                    <p class="c-text c-text--right">（円）</p>
                </div>
                <div class="p-project__content">
                    <p class="c-text">【 内容 】</p>
                    {{ project.content }}
                </div>
            </div>
        </section>

        <!-- ページネーション -->
        <section class="p-pagination">
            
            <!-- 最初のページへ -->
            <button
                v-if="currentPage > 1"
                class="p-pagination__button c-button"
                @click="changePage(1)"
            >
                <
            </button>

            <!-- 前のページボタン -->
            <button
                v-if="currentPage > 2"
                class="p-pagination__button c-button"
                @click="changePage(currentPage - 1)"
            >
                prev
            </button>

            <!-- 動的に変化するページボタン -->
            <button
                v-for="pageNumber in visiblePageNumbers"
                :key="pageNumber"
                class="p-pagination__button c-button"
                :class="{ active: currentPage === pageNumber }"
                @click="changePage(pageNumber)"
            >
                {{ pageNumber }}
            </button>

            <!-- 次のページボタン -->
            <button
                v-if="currentPage < totalPages - 1"
                class="p-pagination__button c-button"
                @click="changePage(currentPage + 1)"
            >
                next
            </button>

            <!-- 最終ページ -->
            <button
                v-if="currentPage < totalPages"
                class="p-pagination__button c-button"
                @click="changePage(totalPages)"
            >
                >
            </button>
        </section>

    </div>
</template>

<script>
import axios from 'axios';

export default {
    
    data() {
        return {
        projects: [],
        currentPage: 1,
        projectsPerPage: 3, //　ページネーションのテスト用にとりあえず3に
        selectedProjectType: '',
        };
    },

    async mounted() {
        await this.getProject(); // ページ読み込み時に案件情報を取得
        this.filterAndPaginateProjects(); // 初回データ取得後にフィルタリングとページネーションを適用
    },

    computed: {

        // 現在のページの開始インデックスと終了インデックスを計算する
        startIndex() {
            return (this.currentPage - 1) * this.projectsPerPage;
        },

        endIndex() {
            return this.startIndex + this.projectsPerPage;
        },

        // フィルタリングされた案件のリスト
        filteredProjects() {
            if (this.selectedProjectType === '') {
                return this.projects; // 絞り込まない場合、すべての案件を表示
            }

            return this.projects.filter((project) => project.type.id.toString() === this.selectedProjectType);
        },

        // フィルタリングとページネーションを適用したプロジェクトリスト
        paginatedProjects: {
            get() {
                // フィルタリング
                const filteredProjects = this.filteredProjects;

                // ページネーション
                const startIndex = this.startIndex;
                const endIndex = this.endIndex;
                return filteredProjects.slice(startIndex, endIndex);
            },
            set(value) {
                // このセッターは読み取り専用のため、何も行わない
            },
        },

        // フィルタリングされた案件の長さとprojectsPerPageを元に、総ページ数を計算する
        totalPages() {
            return Math.ceil(this.filteredProjects.length / this.projectsPerPage);
        },

        // ページネーションで表示するページ番号のリストを取得
        visiblePageNumbers() {
            const totalPages = this.totalPages;
            const currentPage = this.currentPage;

            const maxVisiblePages = 3;

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
        getProject() {
            axios
            .get('/api/projects')
            .then((response) => {
                this.projects = response.data.projects;
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


        // ページネーションのページ変更を処理する
        changePage(pageNumber) {
            this.currentPage = pageNumber;
        },

        // プロジェクトデータのフィルタリングとページネーションを行う
        filterAndPaginateProjects() {
            // フィルタリング
            const filteredProjects = this.filteredProjects;

            // ページネーション
            const startIndex = (this.currentPage - 1) * this.projectsPerPage;
            const endIndex = startIndex + this.projectsPerPage;
            const paginatedProjects = filteredProjects.slice(startIndex, endIndex);

            // ページネーション後のプロジェクトリストをセット
            this.paginatedProjects = paginatedProjects;
        },

        // プロジェクトタイプで絞り込む
        filterProjects() {
            this.currentPage = 1; // ページをリセット
            this.filterAndPaginateProjects(); // フィルタリングとページネーションを適用
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


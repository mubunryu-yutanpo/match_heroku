<template>
    <div class="">
        
        <!-- ソート -->
        <div class="p-filter">
            <label for="projectTypeFilter">プロジェクトタイプ:</label>
            <select v-model="selectedProjectType" @change="filterProjects">
                <option value="">すべて</option>
                <option value="1">単発案件</option>
                <option value="2">レベニューシェア案件</option>
            </select>
        </div>

        <!-- 一覧 -->
        <section class="">

            <!-- 各案件 -->
            <div class="p-card" v-for="project in filteredProjects" :key="project.id">
                <p class="c-text p-card__type">{{ project.type.name }}</p>
                <h3 class="c-tittle p-card__title">{{ project.title }}</h3>
                <p class="c-text p-card__price">{{ project.upperPrice }}</p>
                <p class="c-text p-card__price">{{ project.lowerPrice }}</p>
                <p class="c-text p-card__content">{{ project.content }}</p>
                <a :href="'/project/' + project.id + '/detail'" class="c-link p-card__link">詳細</a>
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
// import { mapState } from 'vuex';

export default {
//   props: ['category'],
  data() {
    return {
      projects: [],
      currentPage: 1,
      projectsPerPage: 1,
      selectedProjectType: '',
    };
  },

  async mounted() {
    await this.getProject(); // ページ読み込み時に案件情報を取得
  },

  computed: {

    // フィルタリングされた案件の長さとprojectsPerPageを元に、総ページ数を計算する
    totalPages() {
      return Math.ceil(this.filteredProjects.length / this.projectsPerPage);
    },

    // 現在のページの開始インデックスと終了インデックスを計算する
    startIndex() {
      return (this.currentPage - 1) * this.projectsPerPage;
    },
    endIndex() {
      return this.startIndex + this.projectsPerPage;
    },

    // 現在のページの案件を取得する
    paginatedProjects() {
      return this.filteredProjects.slice(this.startIndex, this.endIndex);
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

    // 案件種別で絞り込まれた案件のリスト
    filteredProjects() {
        if (this.selectedProjectType === '') {
            return this.projects; // 絞り込まない場合、すべての案件を表示
        }
        console.log(this.projects);
        // 絞り込んだ内容の案件を配列に入れ直す
        this.projects.forEach(project => {
        console.log(typeof project.type);
        console.log(typeof this.selectedProjectType);
        });

        // 案件種別がstring型になるので、そこを考慮して比較
        return this.projects.filter((project) => project.type.id.toString() === this.selectedProjectType);
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

    // // 「気になる」の状態をトグル
    // toggleCheck(id) {
    //   axios.post('/api/idea/' + id + '/toggleCheck')
    //     .then(response => {
    //       console.log('気になるのトグル処理成功');
    //       this.isChecked = !this.isChecked; // チェックボックスの状態を反転させる
    //       this.getprojects();
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

    // プロジェクトタイプで絞り込む
    filterProjects() {
        this.currentPage = 1; // ページをリセット
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


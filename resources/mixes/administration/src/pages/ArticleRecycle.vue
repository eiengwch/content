<script>
    import injection from '../helpers/injection';

    export default {
        beforeRouteEnter(to, from, next) {
            injection.loading.start();
            injection.http.post(`${window.api}/content/article/fetch`, {
                trashed: true,
            }).then(response => {
                const list = response.data.data;
                const pagination = response.data.pagination;
                next(vm => {
                    list.forEach(article => {
                        article.loading = false;
                        article.restoring = false;
                    });
                    vm.list = list;
                    vm.pagination = pagination;
                    injection.loading.finish();
                    injection.message.info(injection.trans('content.article.list.info.get'));
                    injection.sidebar.active('content');
                });
            }).catch(() => {
                injection.loading.fail();
            });
        },
        data() {
            return {
                columns: [
                    {
                        align: 'center',
                        type: 'selection',
                        width: 60,
                    },
                    {
                        key: 'title',
                        title: injection.trans('content.article.recycle.table.title'),
                    },
                    {
                        key: 'author',
                        title: injection.trans('content.article.recycle.table.author'),
                        width: 200,
                    },
                    {
                        key: 'handle',
                        render(row, column, index) {
                            return `
                                    <i-button :loading="list[${index}].restoring" size="small" type="primary" @click.native="restore(${index})">
                                        <span v-if="!list[${index}].restoring">${injection.trans('content.global.restore.submit')}</span>
                                        <span v-else>${injection.trans('content.global.restore.loading')}</span></i-button>
                                    <i-button :loading="list[${index}].loading" size="small" type="error" @click.native="remove(${index})">
                                        <span v-if="!list[${index}].loading">${injection.trans('content.global.delete.submit')}</span>
                                        <span v-else>${injection.trans('content.global.delete.loading')}</span>
                                    </i-button>
                                    `;
                        },
                        title: injection.trans('content.article.recycle.table.handle'),
                        width: 200,
                    },
                ],
                list: [],
                loading: false,
                pagination: {},
                selections: [],
                self: this,
                trans: injection.trans,
            };
        },
        methods: {
            paginator(id) {
                const self = this;
                self.$loading.start();
                if (self.categories.id === 'none') {
                    self.$http.post(`${window.api}/content/article/fetch`, {
                        'only-no-category': true,
                        page: id,
                    }).then(response => {
                        const result = response.data;
                        result.data.forEach(item => {
                            item.loading = false;
                            item.restoring = false;
                        });
                        self.list = result.data;
                        self.pagination = result.pagination;
                        self.$loading.finish();
                        self.$message.info(injection.trans('content.article.list.info.get'));
                    }).catch(() => {
                        self.$loading.fail();
                    });
                } else {
                    self.$http.post(`${window.api}/content/article/fetch`, {
                        category: self.categories.id,
                        page: id,
                    }).then(response => {
                        const result = response.data;
                        result.data.forEach(item => {
                            item.loading = false;
                            item.restoring = false;
                        });
                        self.list = result.data;
                        self.pagination = result.pagination;
                        self.$loading.finish();
                        self.$message.info(injection.trans('content.article.list.info.get'));
                    }).catch(() => {
                        self.$loading.fail();
                    });
                }
            },
            remove(index) {
                const self = this;
                const article = self.list[index];
                article.loading = true;
                self.$http.post(`${window.api}/content/article/delete`, {
                    id: article.id,
                    force: true,
                }).then(response => {
                    const result = response.data;
                    result.data.forEach(item => {
                        item.loading = false;
                        item.restoring = false;
                    });
                    self.list = result.data;
                    self.pagination = result.pagination;
                    self.$notice.open({
                        title: injection.trans('content.article.info.force'),
                    });
                }).finally(() => {
                    article.loading = false;
                });
            },
            removeSelected() {
                const self = this;
                self.$loading.start();
                self.loading = true;
                if (self.selections.length === 0) {
                    self.$loading.finish();
                    self.$notice.error({
                        title: injection.trans('content.article.list.info.none'),
                    });
                    self.loading = false;
                } else {
                    self.selections.forEach((article, key) => {
                        self.$http.post(`${window.api}/content/article/delete`, {
                            id: article.id,
                            force: true,
                        }).then(response => {
                            const result = response.data;
                            result.data.forEach(item => {
                                item.loading = false;
                            });
                            self.list = result.data;
                            self.pagination = result.pagination;
                            self.$notice.open({
                                title: injection.trans('content.article.info.force'),
                            });
                        }).finally(() => {
                            if (self.selections.length === key + 1) {
                                self.$loading.finish();
                                self.loading = false;
                            }
                        });
                    });
                }
            },
            restore(index) {
                const self = this;
                const article = self.list[index];
                article.restoring = true;
                self.$http.post(`${window.api}/content/article/delete`, {
                    id: article.id,
                    restore: true,
                }).then(response => {
                    const result = response.data;
                    result.data.forEach(item => {
                        item.loading = false;
                        item.restoring = false;
                    });
                    self.list = result.data;
                    self.pagination = result.pagination;
                    self.$notice.open({
                        title: injection.trans('content.article.info.restore'),
                    });
                }).finally(() => {
                    article.restoring = false;
                });
            },
            selection(items) {
                this.selections = items;
            },
        },
    };
</script>
<template>
    <div class="article-wrap">
        <div class="article-list">
            <card :bordered="false">
                <template slot="title">
                    <div class="filter">
                        <!--<i-select clearable style="width:200px">-->
                            <!--&lt;!&ndash;<i-option v-for="item in cityList" :value="item.value">{{ item.label }}</i-option>&ndash;&gt;-->
                        <!--</i-select>-->
                        <i-button :loading="loading" type="error" @click.native="removeSelected">
                            <span v-if="!loading">{{ trans('content.global.delete.submit') }}</span>
                            <span v-else>{{ trans('content.global.delete.batch.loading') }}</span>
                        </i-button>
                    </div>
                </template>
                <i-table :columns="columns" :context="self" :data="list" @on-selection-change="selection"></i-table>
                <div class="ivu-page-wrap">
                    <page :current="pagination.current_page" :page-size="pagination.per_page" :total="pagination.total" @on-change="paginator"></page>
                </div>
            </card>
        </div>
    </div>
</template>
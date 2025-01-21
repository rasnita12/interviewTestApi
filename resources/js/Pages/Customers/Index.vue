<template>
  <div>
    <Head title="Customers" />
    <div class="flex flex-row items-center">
      <h1 class="mb-4 text-3xl font-bold mr-3">Customers</h1>
      <a-breadcrumb>
        <a-breadcrumb-item><Link :href="route('dashboard')">Dashbaord</Link></a-breadcrumb-item>
        <a-breadcrumb-item>Customers</a-breadcrumb-item>
      </a-breadcrumb>
    </div>

    <a-card bordered>
      <div class="w-60 float-right">
        <text-input name="name" v-model="search" placeholder="Search" size="default" :allow-clear="true" :prefix-icon="searchIcon"></text-input>
      </div>

      <a-table
        :loading="loading"
        :dataSource="data?.data ?? []"
        :columns="columns"
        :pagination="{
          current: data?.current_page ?? 1,
          pageSize: data?.per_page ?? 10,
          total: data?.total ?? 0,
          showSizeChanger: true,
          showTotal: (total) => `Total ${total} items`,
        }"
        @change="handleSorting"
        bordered
        :scroll="{ x: 'max-content' }"
      >
        <template #bodyCell="{ column, text, record }">
          <template v-if="column.dataIndex === 'action'">
            <a-button @click="viewHistory(record)" type="primary" :style="{ backgroundColor: 'orange' }" size="middle">
              <template #icon><i class="fa fa-eye"></i></template>
            </a-button>
          </template>
        </template>
      </a-table>
    </a-card>

    <a-modal v-if="open" v-model:open="open" :title="`History of ${user.first_name} ${user.last_name}`" @ok="handleOk">
      <a-table :loading="historyLoading" :columns="historyColumns" :data-source="histories">
        <template #bodyCell="{ column, text, record }">
          <template v-if="column.dataIndex === 'created_at'">
            {{ dayjs(record.created_at).format('DD/MM/YYYY HH:mm') }}
          </template>
        </template>
      </a-table>
    </a-modal>
  </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import axios from 'axios'
import { SearchOutlined } from '@ant-design/icons-vue'
import TextInput from '../../Shared/TextInput.vue'
import debounce from 'lodash/debounce.js'
import dayjs from 'dayjs'

export default {
  components: {
    Head,
    Link,
    TextInput,
  },
  layout: Layout,

  data() {
    return {
      columns: [
        {
          title: 'First Name',
          dataIndex: 'first_name',
          key: 'first_name',
          sorter: true,
          showSorterTooltip: false,
        },
        {
          title: 'Last Name',
          key: 'last_name',
          dataIndex: 'last_name',
          sorter: true,
          showSorterTooltip: false,
        },
        {
          title: 'Email',
          key: 'email',
          dataIndex: 'email',
          sorter: true,
          showSorterTooltip: false,
        },
        {
          title: 'Action',
          key: 'action',
          dataIndex: 'action',
        },
      ],
      historyColumns: [
        {
          title: 'Login At',
          dataIndex: 'created_at',
          key: 'created_at',
          sorter: false,
          showSorterTooltip: false,
        },
        {
          title: 'City',
          dataIndex: 'city',
          key: 'city',
          sorter: false,
          showSorterTooltip: false,
        },
        {
          title: 'IP Address',
          dataIndex: 'ip',
          key: 'ip',
          sorter: false,
          showSorterTooltip: false,
        },
      ],
      data: [],
      searchIcon: SearchOutlined,
      loading: false,
      search: '',
      tableForm: {
        size: 10,
        page: null,
        field: null,
        direction: null,
      },
      open: false,
      user: null,
      histories: [],
      historyLoading: false,
    }
  },

  watch: {
    search: debounce(function (newValue) {
      this.initialize()
    }, 300),
  },

  mounted() {
    this.initialize()
  },

  methods: {
    dayjs,

    viewHistory(user) {
      this.user = user
      this.open = true
      this.fetchHistories()
    },

    handleOk() {
      this.user = null
      this.open = false
    },

    async fetchHistories() {
      this.historyLoading = true
      await axios
        .get(this.route('customers.history', { id: this.user.id }))
        .then((response) => {
          this.histories = response.data
        })
        .catch((error) => {
          this.$message.error(error.message)
        })
        .finally(() => {
          this.historyLoading = false
        })
    },

    handleSorting(pagination, filters, sorter) {
      if (pagination) {
        const { current, pageSize } = pagination
        this.tableForm.size = pageSize
        this.tableForm.page = current !== 1 ? current : null
      }
      if (sorter) {
        if (sorter.column && sorter.order) {
          this.tableForm.field = sorter.field
          this.tableForm.direction = sorter.order === 'descend' ? 'desc' : 'asc'
        } else {
          this.tableForm.field = null
          this.tableForm.direction = null
        }
        this.initialize()
      }
    },

    async initialize() {
      this.loading = true
      await axios
        .get(this.route('customers.lists', {
          search: this.search,
          field: this.tableForm.field,
          direction: this.tableForm.direction,
          page: this.tableForm.page,
          size: this.tableForm.size,
        }))
        .then((response) => {
          this.data = response.data
        })
        .catch((error) => {
          this.$message.error(error.message)
        })
        .finally(() => {
          this.loading = false
        })
    },
  },
}
</script>

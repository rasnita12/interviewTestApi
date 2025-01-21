<template>
  <div>
    <Head title="Health Centers" />
    <div class="flex items-center justify-between">
      <div class="flex flex-row items-center">
        <h1 class="mb-4 text-3xl font-bold mr-3">Health Centers</h1>
        <a-breadcrumb>
          <a-breadcrumb-item><Link :href="route('dashboard')">Dashbaord</Link></a-breadcrumb-item>
          <a-breadcrumb-item>Health Centers</a-breadcrumb-item>
        </a-breadcrumb>
      </div>
      <Link :href="this.route('health-centers.create')">
        <a-button html-type="link" type="primary" size="middle">
          <template #icon><PlusOutlined /></template>
          Create
        </a-button>
      </Link>
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
            <Link :href="this.route('health-centers.edit', { id: record.id })">
              <a-button cl html-type="link" type="primary" size="middle">
                <template #icon><EditOutlined /></template>
              </a-button>
            </Link>
            <a-popconfirm placement="topRight" title="Are you sure?" @confirm="deleteConfirm(record.id)">
              <template #icon><QuestionCircleOutlined style="color: red" /></template>
              <a-button class="ml-2" type="primary" size="middle" danger>
                <template #icon><DeleteOutlined /></template>
              </a-button>
            </a-popconfirm>
          </template>
          <template v-if="column.dataIndex === 'active'">
            <a-badge :count="[true, 'true'].includes(record.active) ? 'Yes' : 'No'" :number-style="{ backgroundColor: [true, 'true'].includes(record.active) ? '#52c41a' : '' }" />
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
import { SearchOutlined, PlusOutlined, EditOutlined, QuestionCircleOutlined, DeleteOutlined } from '@ant-design/icons-vue'
import TextInput from '../../../Shared/TextInput.vue'
import debounce from 'lodash/debounce.js'
import dayjs from 'dayjs'

export default {
  components: {
    Head,
    Link,
    TextInput,
    PlusOutlined,
    EditOutlined,
    QuestionCircleOutlined,
    DeleteOutlined,
  },
  layout: Layout,

  data() {
    return {
      columns: [
        {
          title: 'Name',
          dataIndex: 'name',
          key: 'name',
          sorter: true,
          showSorterTooltip: false,
        },
        {
          title: 'Phone',
          key: 'phone',
          dataIndex: 'phone',
          sorter: true,
          showSorterTooltip: false,
        },
        {
          title: 'Active',
          dataIndex: 'active',
          key: 'active',
          sorter: false,
          showSorterTooltip: false,
        },
        {
          title: 'Line 1',
          key: 'line_1',
          dataIndex: 'line_1',
          sorter: true,
          showSorterTooltip: false,
        },
        {
          title: 'Postcode',
          key: 'postcode',
          dataIndex: 'postcode',
          sorter: true,
          showSorterTooltip: false,
        },
        {
          title: 'Action',
          key: 'action',
          dataIndex: 'action',
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

    async deleteConfirm(id) {
      this.loading = true

      await axios
        .delete(this.route('health-centers.delete', { id: id }))
        .then((response) => {
          this.$message.success(response.data.message)
          this.initialize()
        })
        .catch((error) => {
          console.log(error)
        })
        .finally(() => {
          this.loading = false
        })
    },

    async initialize() {
      this.loading = true
      await axios
        .get(
          this.route('health-centers.lists', {
            search: this.search,
            field: this.tableForm.field,
            direction: this.tableForm.direction,
            page: this.tableForm.page,
            size: this.tableForm.size,
          }),
        )
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

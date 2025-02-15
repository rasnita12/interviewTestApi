<template>
  <div>
    <Head title="Customers" />
    <div class="flex items-center justify-between mb-4">
      <Breadcrumb title="Customers">
        <a-breadcrumb-item>Customers</a-breadcrumb-item>
      </Breadcrumb>

        <a-button @click="dialog = true" html-type="link" type="primary" size="middle">
          <template #icon><PlusOutlined /></template>
          Create
        </a-button>
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
            <a-space :size="4">
              <a-button @click="preEdit(record.id)" type="primary" :style="{ backgroundColor: '#1f2835' }" size="middle">
                <template #icon><i class="fa fa-edit"></i></template>
              </a-button>
              <a-button @click="viewHistory(record)" type="primary" :style="{ backgroundColor: 'orange' }" size="middle">
                <template #icon><i class="fa fa-eye"></i></template>
              </a-button>
              <a-button @click.prevent="showPasswordForm(record)" type="primary" size="middle" :style="{ backgroundColor:'#5fc62b' }" >
                <template #icon><LockOutlined /></template>
              </a-button>
            </a-space>
          </template>
        </template>
      </a-table>
    </a-card>

    <CustomerForm v-if="dialog" :editItem="editItem" :show-modal="dialog" @refresh-data="initialize" @close-modal="dialog = !dialog" />

    <UserPassword v-if="passwordForm.dialog" :user="passwordForm.user" :show-modal="passwordForm.dialog" @close-modal="closePasswordForm" />

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
import { SearchOutlined, PlusOutlined, EditOutlined, LockOutlined } from '@ant-design/icons-vue'
import TextInput from '../../../Shared/TextInput.vue'
import debounce from 'lodash/debounce.js'
import dayjs from 'dayjs'
import Breadcrumb from '../../../Components/Shared/Breadcrumb.vue'
import CustomerForm from '../../../Components/Admin/Form/Customer/Form.vue'
import UserPassword from '../../../Components/Admin/Form/User/UserPassword.vue'

export default {
  components: {
    UserPassword,
    CustomerForm,
    Breadcrumb,
    Head,
    Link,
    TextInput,
    PlusOutlined,
    EditOutlined,
    LockOutlined
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
          title: 'Username',
          key: 'username',
          dataIndex: 'username',
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

      dialog: false,
      editItem: null,

      passwordForm: {
        dialog: false,
        user: null,
      }
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

    showPasswordForm(user) {
      this.passwordForm.user = user
      this.passwordForm.dialog = true
    },

    closePasswordForm() {
      this.passwordForm.dialog = false;
      this.passwordForm.user = null
    },

    preEdit(id) {
        this.editItem = id;
        this.dialog = true
    },

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
        .get(this.route('users.histories', { id: this.user.id }))
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

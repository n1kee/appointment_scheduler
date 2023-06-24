<template>
  <div class="q-pa-md">
    <q-table
      v-if="pagination"
      ref="table"
      title="Записи на прием"
      :data="data"
      :columns="columns"
      :pagination.sync="pagination"
      :loading="loadingList"
      row-key="id"
      :filter="filter"
      :rows-per-page-options="rowsPerPageOpts"
      @request="onRequest"
    >
      <template #top>
        <div class="q-pa-md fit">
          <div class="q-px-md q-pt-md q-gutter-md row">
            Фильрация по дате записи:
          </div>
          <div class="q-py-md q-gutter-md row">
            <q-input
              v-model="dateTimeFrom"
              label="От даты"
              filled
              readonly
            >
              <template #prepend>
                <q-icon
                  name="event"
                  class="cursor-pointer"
                >
                  <q-popup-proxy
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-date
                      v-model="dateTimeFrom"
                      mask="YYYY-MM-DD HH:mm"
                      :navigation-min-year-month="minSelectionMonth"
                      @input="setDateTimeFrom"
                    >
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>

              <template #append>
                <q-icon
                  name="access_time"
                  class="cursor-pointer"
                >
                  <q-popup-proxy
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-time
                      v-model="dateTimeFrom"
                      mask="YYYY-MM-DD HH:mm"
                      format24h
                      @input="setDateTimeFrom"
                    >
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-time>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
            <q-input
              v-model="dateTimeTo"
              label="До даты"
              filled
              readonly
            >
              <template #prepend>
                <q-icon
                  name="event"
                  class="cursor-pointer"
                >
                  <q-popup-proxy
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-date
                      v-model="dateTimeTo"
                      mask="YYYY-MM-DD HH:mm"
                      :navigation-min-year-month="minSelectionMonth"
                      @input="setDateTimeTo"
                    >
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>

              <template #append>
                <q-icon
                  name="access_time"
                  class="cursor-pointer"
                >
                  <q-popup-proxy
                    transition-show="scale"
                    transition-hide="scale"
                  >
                    <q-time
                      v-model="dateTimeTo"
                      mask="YYYY-MM-DD HH:mm"
                      format24h
                      @input="setDateTimeTo"
                    >
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-time>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>
          <div class="q-py-md q-gutter-md row">
            <q-input
              v-model="doctorSearch"
              class="fit"
              filled
              name="firstname"
              label="Поиск по доктору"
              lazy-rules
              @input="() => loadList()"
            />
          </div>
          <div class="q-py-md q-gutter-md row">
            <q-input
              v-model="patientSearch"
              class="fit"
              filled
              name="firstname"
              label="Поиск по пациенту"
              lazy-rules
              @input="() => loadList()"
            />
          </div>
        </div>
      </template>
    </q-table>
  </div>
</template>

<script>
export default {
    data () {
        const dateNow = new Date()
        const month = dateNow.getMonth() + 1
        const monthString = `0${month}`.slice(-2)

        return {
            doctorSearch: null,
            patientSearch: null,
            dateTimeFrom: null,
            dateTimeTo: null,
            loadingList: false,
            minSelectionMonth: `${dateNow.getFullYear()}/${monthString}`,
            pagination: {},
            filter: {},
            rowsPerPageOpts: [],
            columns: [
                { name: 'datetime', label: 'Дата и время', field: 'datetime', sortable: true },
                { name: 'patient', label: 'Пациент', field: 'patient' },
                { name: 'doctor', label: 'Доктор', field: 'doctor' }
            ],
            data: []
        }
    },
    mounted () {
        this.loadList()
    },
    methods: {
        onRequest (requestProp) {
            this.loadList(requestProp.pagination.page, !!requestProp.pagination.sortBy)
        },
        setDateTimeFrom (date) {
            this.filter.from = date
            this.loadListOnFilter()
        },
        setDateTimeTo (date) {
            this.filter.to = date
            this.loadListOnFilter()
        },
        loadListOnFilter (page, datetimeDescSort) {
            this.loadList(page, datetimeDescSort)
        },
        loadList (page, datetimeDescSort) {
            page = page || this.pagination.page || 1

            this.loadingList = true

            axios
                .get(`/api/appointments`, {
                    params: {
                        page,
                        datetimeSort: datetimeDescSort ? 'desc' : 'asc',
                        from: this.filter.from,
                        to: this.filter.to,
                        doctor: this.doctorSearch,
                        patient: this.patientSearch,
                        min: this.patientSearch
                    }
                })
                .then(response => {
                    this.data = response.data.data
                    this.rowsPerPageOpts[0] = response.data.per_page
                    this.pagination.descending = datetimeDescSort
                    this.pagination.sortBy = 'datetime'
                    this.pagination.page = response.data.current_page
                    this.pagination.rowsPerPage = response.data.per_page
                    this.pagination.rowsNumber = response.data.total

                    this.$refs.table.setPagination(this.pagination)

                    this.loadingList = false
                })
        }
    }
}
</script>

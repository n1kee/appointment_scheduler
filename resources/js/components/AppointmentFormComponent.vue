<template>
  <div>
    <div class="q-pa-md float-left">
      <q-form
        ref="form"
        class="q-gutter-md"
        @submit.prevent="onSubmit"
      >
        <q-select
          filled
          :value="doctor"
          :option-value="'id'"
          :option-label="'fullName'"
          :options="doctorList"
          label="Выберите доктора"
          :error="!!errors.doctor_id"
          :error-message="errors.doctor_id?.join('')"
          @input="setDoctor"
        />
        <q-select
          filled
          :value="patient"
          :option-value="'id'"
          :option-label="'fullName'"
          :options="patientList"
          label="Выберите пациента"
          :error="!!errors.patient_id"
          :error-message="errors.patient_id?.join('')"
          @input="setPatient"
        />
        <q-input
          v-if="doctor"
          v-model="date"
          filled
          label="Дата записи"
          lazy-error-message
          readonly
          :error="!!errors.datetime"
          :error-message="errors.datetime?.join('')"
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
                  v-model="datetime"
                  minimal
                  mask="YYYY-MM-DD HH:mm"
                  :navigation-min-year-month="minSelectionMonth"
                  @input="setDate"
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
        </q-input>
        <div v-if="datetime">
          <q-input
            v-model="time"
            filled
            label="Время записи"
            lazy-error-message
            readonly
            :error="!!errors.datetime"
            :error-message="errors.datetime?.join('')"
          >
            <template #prepend>
              <q-icon
                name="access_time"
                class="cursor-pointer"
              >
                <q-popup-proxy
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-time
                    v-model="datetime"
                    mask="YYYY-MM-DD HH:mm"
                    format24h
                    dark
                    bordered
                    @input="setDate"
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
        <q-input
          :value="doctor?.id"
          class="hidden"
          name="doctor_id"
        />
        <q-input
          :value="patient?.id"
          class="hidden"
          name="patient_id"
        />
        <q-input
          :value="datetime"
          class="hidden"
          name="datetime"
        />
        <div class="row">
          <q-btn
            label="Сохранить"
            type="submit"
            color="primary"
          />
        </div>
      </q-form>
    </div>
    <div
      v-if="availableIntervalsList"
      class="q-pa-xl float-left"
    >
      <div class="q-gutter-md">
        <div class="row">
          Доступное время для записи на {{ date }}:
        </div>
        <div
          v-for="(availableInterval, index) in availableIntervalsList"
          :key="index"
          class="row"
        >
          с {{ availableInterval.from }} до {{ availableInterval.to }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import formHelper from '../helpers/FormHelper.js'

export default {
    data () {
        const dateNow = new Date()
        const month = dateNow.getMonth() + 1
        const monthString = `0${month}`.slice(-2)

        return {
            errors: {},
            doctor: null,
            patient: null,
            date: null,
            time: null,
            datetime: null,
            doctorList: null,
            patientList: null,
            availableIntervalsList: null,
            minSelectionMonth: `${dateNow.getFullYear()}/${monthString}`
        }
    },
    mounted () {
        axios
            .get('/api/doctors')
            .then(response => (this.doctorList = response.data))
        axios
            .get('/api/patients')
            .then(response => (this.patientList = response.data))
    },
    methods: {
        setDoctor (doctor) {
            this.doctor = doctor
            this.getAvailableIntervals()
        },
        setPatient (patient) {
            this.patient = patient
        },
        setDate (date) {
            var time = null
            if (date) {
                [date, time] = date.split(' ')
            }
            this.date = date
            this.time = time
            this.getAvailableIntervals()
        },
        getAvailableIntervals () {
            if (!this.date) return

            axios
                .get('/api/appointments/available', {
                    params: {
                        date: this.date,
                        doctor: this.doctor.id
                    }
                })
                .then(response => (this.availableIntervalsList = response.data))
        },
        onSubmit (submitEvent) {
            formHelper.submitForm('/api/appointments', submitEvent.target)
                .then(response => this.$router.push('/'))
                .catch(error => this.errors = error.response.data?.errors)
        }
    }
}
</script>

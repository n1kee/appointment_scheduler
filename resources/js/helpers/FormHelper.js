
export default {

    submitForm (url, formDomElem) {
        const parsedForm = this.parseForm(formDomElem)

        return new Promise((resolve, reject) => {
            axios.post(url, parsedForm.data)
                .then(res => {
                    alert('Успешно!')
                    resolve(res)
                })
                .catch(error => {
                    if (!error.response.data.errors) {
                        alert('Произошла непредвиденная ошибка!')
                    }
                    reject(error)
                }).finally(() => {
                })
        })
    },

    parseForm (formDomElem) {
        const elements = [].slice.call(formDomElem.elements)
        const data = elements.reduce((form, elem) => {
            if (elem.name) form[ elem.name ] = elem.value
            return form
        }, {})
        return { elements, data }
    }

}

/**
 * Theme js
 */

/* Imports */

import { setElements, usingMouse, request } from 'Formation/utils'

/* Classes */

import Nav from 'Formation/components/nav'
import SendForm from 'Formation/objects/form/send'

/* Variables */

const ns = window.namespace
const n = window[ns]

const el = {}

const meta = [
  {
    prop: 'nav',
    selector: '.c-nav',
    items: [
      {
        prop: 'navList',
        selector: '.c-nav__list'
      },
      {
        prop: 'navOverflow',
        selector: '.c-nav-overflow'
      },
      {
        prop: 'navOverflowList',
        selector: '.c-nav-overflow__list'
      },
      {
        prop: 'navItems',
        selector: '.c-nav__item[data-depth="0"]',
        all: true
      },
      {
        prop: 'navLinks',
        selector: '.c-nav__link',
        all: true
      },
      {
        prop: 'navButton',
        selector: '.c-nav__button'
      },
      {
        prop: 'navOverlay',
        selector: '.c-nav__overlay'
      }
    ]
  },
  {
    prop: 'forms',
    selector: `.js-${ns}-form`,
    all: true,
    array: true
  }
]

/* Init */

const initialize = () => {
  /* Set elements object */

  setElements(document, meta, el)

  /* Check if using mouse */

  usingMouse()

  /* Navigation */

  if (el.nav) {
    const nav = () => {
      const itemSelector = '.c-nav__item[data-depth="0"]'

      return new Nav({
        nav: el.nav,
        list: el.navList,
        overflow: el.navOverflow,
        overflowList: el.navOverflowList,
        items: el.navItems,
        itemSelector,
        links: el.navLinks,
        button: el.navButton,
        overlay: el.navOverlay
      })
    }

    nav()
  }

  /* Forms */

  if (el.forms.length) {
    const sendForm = (form, nonce = null) => {
      const id = form.id
      const type = form.getAttribute('data-type')
      const result = form.querySelector('.o-result')
      const args = {
        id,
        form,
        data: {
          action: 'send_form',
          type,
          nonce,
          nonce_name: id
        },
        url: n.ajax_url,
        inputs: form.querySelectorAll('.js-input'),
        submit: form.querySelector('.js-submit'),
        loaders: form.querySelectorAll('.o-loader'),
        groupClass: 'o-form-group__bottom',
        fieldClass: 'o-form__field',
        labelClass: 'o-form__label',
        shake: true,
        result: {
          container: result,
          textContainer: result.querySelector('.o-result__text')
        }
      }

      if (form.hasAttribute('data-location')) { args.data.location = form.getAttribute('data-location') }

      if (n.hasOwnProperty(`form_${id}`)) {
        const success = n[`form_${id}`].success_message

        if (success) {
          args.result.text = {
            success
          }
        }
      }

      const sf = new SendForm(args)
    }

    el.forms.forEach(form => {
      request({
        method: 'POST',
        url: n.ajax_url,
        headers: { 'Content-type': 'application/x-www-form-urlencoded' },
        body: `action=create_nonce&nonce_name=${form.id}`
      })
        .then(response => {
          sendForm(form, JSON.parse(response).nonce)
        })
        .catch(xhr => {
          console.log(xhr)
        })
    })
  }
}

initialize()

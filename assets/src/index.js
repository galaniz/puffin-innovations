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

  /* Get scrollbar width */

  let resizeTimer

  const getScrollbarWidth = () => {
    const html = document.documentElement
    const w = window.innerWidth - html.clientWidth

    html.style.setProperty('--scrollbar-width', `${w}px`)
  }

  const resizeHandler = () => {
    clearTimeout(resizeTimer)

    resizeTimer = setTimeout(() => {
      getScrollbarWidth()
    }, 100)
  }

  getScrollbarWidth()

  window.addEventListener('resize', resizeHandler)

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
      /* Store instance */

      let instance = null

      /* Get elements */

      const meta = [
        {
          prop: 'inputs',
          selector: '.js-input',
          all: true
        },
        {
          prop: 'submit',
          selector: '.js-submit'
        },
        {
          prop: 'loaders',
          selector: '.o-loader',
          all: true
        },
        {
          prop: 'errorSummary',
          selector: '.o-form-error__summary'
        },
        {
          prop: 'errorSummaryList',
          selector: '.o-form-error__summary ul'
        },
        {
          prop: 'errorSummaryList',
          selector: '.o-form-error__summary ul'
        },
        {
          prop: 'error',
          selector: '.o-form-result__negative'
        },
        {
          prop: 'errorPrimary',
          selector: '.o-form-result__negative .o-form-result__primary'
        },
        {
          prop: 'errorSecondary',
          selector: '.o-form-result__negative .o-form-result__secondary'
        },
        {
          prop: 'success',
          selector: '.o-form-result__positive'
        },
        {
          prop: 'successPrimary',
          selector: '.o-form-result__positive .o-form-result__primary'
        },
        {
          prop: 'successSecondary',
          selector: '.o-form-result__positive .o-form-result__secondary'
        }
      ]

      const f = {}

      setElements(form, meta, f)

      /* Variables */

      const id = form.id
      const type = form.getAttribute('data-type')

      /* Data */

      const data = {
        action: 'send_form',
        type,
        nonce,
        nonce_name: id
      }

      /* Messages */

      const messages = {
        error: {
          primary: 'Sorry, there is a problem with the service.',
          secondary: 'Try again later.'
        },
        success: {
          primary: 'Success!',
          secondary: ''
        }
      }

      if (Object.getOwnPropertyDescriptor(n, `form_${id}`)) {
        const ff = n[`form_${id}`]

        if (Object.getOwnPropertyDescriptor(ff, 'success_message')) {
          if (ff.success_message.primary) {
            messages.success.primary = ff.success_message.primary
          }

          if (ff.success_message.secondary) {
            messages.success.secondary = ff.success_message.secondary
          }
        }

        if (Object.getOwnPropertyDescriptor(ff, 'error_message')) {
          if (ff.error_message.primary) {
            messages.error.primary = ff.error_message.primary
          }

          if (ff.error_message.secondary) {
            messages.error.secondary = ff.error_message.secondary
          }
        }
      }

      /* Args */

      const args = {
        id,
        form,
        data,
        url: n.ajax_url,
        inputs: f.inputs,
        submit: f.submit,
        loaders: f.loaders,
        groupClass: 'o-form__group',
        fieldClass: 'o-form__field',
        labelClass: 'o-form__label',
        errorTemplate: `
          <span class='o-form__error l-padding-top-4xs l-flex l-gap-margin-4xs' id='%id'>
            <span class='t-line-height-0'>
              <svg width="25" height="25" viewBox="0 0 20 20" fill="none" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg"><path d="M10.0001 14.1666C10.1945 14.1666 10.3577 14.1007 10.4897 13.9687C10.6216 13.8368 10.6876 13.6736 10.6876 13.4791C10.6876 13.2847 10.6216 13.1215 10.4897 12.9895C10.3577 12.8576 10.1945 12.7916 10.0001 12.7916C9.80564 12.7916 9.64244 12.8576 9.5105 12.9895C9.37855 13.1215 9.31258 13.2847 9.31258 13.4791C9.31258 13.6736 9.37855 13.8368 9.5105 13.9687C9.64244 14.1007 9.80564 14.1666 10.0001 14.1666ZM10.0001 18.3333C8.86119 18.3333 7.7848 18.1145 6.77091 17.677C5.75703 17.2395 4.87161 16.6423 4.11466 15.8854C3.35772 15.1284 2.7605 14.243 2.323 13.2291C1.8855 12.2152 1.66675 11.1319 1.66675 9.97913C1.66675 8.84024 1.8855 7.76385 2.323 6.74996C2.7605 5.73607 3.35772 4.85413 4.11466 4.10413C4.87161 3.35413 5.75703 2.76038 6.77091 2.32288C7.7848 1.88538 8.86814 1.66663 10.0209 1.66663C11.1598 1.66663 12.2362 1.88538 13.2501 2.32288C14.264 2.76038 15.1459 3.35413 15.8959 4.10413C16.6459 4.85413 17.2397 5.73607 17.6772 6.74996C18.1147 7.76385 18.3334 8.84718 18.3334 9.99996C18.3334 11.1388 18.1147 12.2152 17.6772 13.2291C17.2397 14.243 16.6459 15.1284 15.8959 15.8854C15.1459 16.6423 14.264 17.2395 13.2501 17.677C12.2362 18.1145 11.1529 18.3333 10.0001 18.3333ZM10.0626 10.9791C10.2431 10.9791 10.3924 10.9201 10.5105 10.802C10.6286 10.684 10.6876 10.5347 10.6876 10.3541V6.33329C10.6876 6.15274 10.6286 6.00343 10.5105 5.88538C10.3924 5.76732 10.2431 5.70829 10.0626 5.70829C9.88203 5.70829 9.73272 5.76732 9.61467 5.88538C9.49661 6.00343 9.43758 6.15274 9.43758 6.33329V10.3541C9.43758 10.5347 9.49661 10.684 9.61467 10.802C9.73272 10.9201 9.88203 10.9791 10.0626 10.9791Z" fill="currentColor"/></svg>
            </span>
            <span class='t-line-height-0'>
              <span class='t-h6' id='%id-text'>%message</span>
            </span>
          </span>
        `,
        result: {
          error: {
            summary: {
              container: f.errorSummary,
              list: f.errorSummaryList
            },
            container: f.error,
            primary: f.errorPrimary,
            secondary: f.errorSecondary,
            message: messages.error
          },
          success: {
            container: f.success,
            primary: f.successPrimary,
            secondary: f.successSecondary,
            message: messages.success
          }
        }
      }

      /* Filter inputs */

      if (type === 'mailchimp' || type === 'contact-mailchimp') {
        args.filterInputs = (formValuesArgs, inputs) => {
          inputs.forEach((input) => {
            if (input.hasAttribute('data-tag')) {
              formValuesArgs.tag = true
            }

            if (input.hasAttribute('data-merge-field')) {
              formValuesArgs.merge_field = input.getAttribute('data-merge-field')
            }

            if (input.hasAttribute('data-mailchimp-consent')) {
              instance.data.mailchimp_consent_name = input.name
            }
          })

          return formValuesArgs
        }
      }

      instance = new SendForm(args)

      return instance
    }

    /* Get nonce */

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

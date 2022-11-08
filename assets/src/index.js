/**
 * Theme js
 */

/* Imports */

import { setElements, usingMouse, request, getKey, getDefaultFontSize } from 'Formation/utils'

/* Classes */

import Nav from 'Formation/components/nav'
import Modal from 'Formation/objects/modal'
import SendForm from 'Formation/objects/form/send'
import Conditional from 'Formation/objects/form/conditional'
import Collapsible from 'Formation/objects/collapsible'
import Slider from 'Formation/objects/slider'

/* Variables */

const ns = window.namespace
const n = window[ns]
const el = {}
const meta = [
  {
    prop: 'logo',
    selector: '#js-logo'
  },
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
        prop: 'navOpen',
        selector: '.c-nav__open'
      },
      {
        prop: 'navClose',
        selector: '.c-nav__close'
      },
      {
        prop: 'navOverlay',
        selector: '.c-nav__overlay'
      }
    ]
  },
  {
    prop: 'search',
    selector: '.c-nav-search',
    items: [
      {
        prop: 'searchButton',
        selector: '.c-nav-search__button'
      },
      {
        prop: 'searchBar',
        selector: '.c-nav-search__bar'
      },
      {
        prop: 'searchInput',
        selector: 'input'
      }
    ]
  },
  {
    prop: 'hero',
    selector: '.js-hero',
    items: [
      {
        prop: 'heroTarget',
        selector: '.js-hero__target'
      }
    ]
  },
  {
    prop: 'modalTriggers',
    selector: '.js-modal-trigger',
    all: true,
    array: true
  },
  {
    prop: 'collapsibles',
    selector: '.o-collapsible',
    all: true
  },
  {
    prop: 'forms',
    selector: `.js-${ns}-form`,
    all: true,
    array: true
  },
  {
    prop: 'conditionals',
    selector: '.js-conditional',
    all: true,
    array: true
  },
  {
    prop: 'fieldsets',
    selector: 'fieldset',
    all: true,
    array: true
  },
  {
    prop: 'slider',
    selector: '.o-slider',
    all: true
  }
]

/* Resize helper */

const onResize = (callback = () => {}, delay = 100) => {
  let resizeTimer

  const resizeHandler = () => {
    clearTimeout(resizeTimer)

    resizeTimer = setTimeout(() => {
      callback()
    }, delay)
  }

  window.addEventListener('resize', resizeHandler)
}

/* Init */

const initialize = () => {
  /* Check if reduce motion */

  let reduceMotion = false

  const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)')

  if (!mediaQuery || mediaQuery.matches) { reduceMotion = true }

  /* Multiplier based on default font size */

  const defaultFontSize = getDefaultFontSize() ?? 16
  const multiplier = defaultFontSize / 16

  /* Set elements object */

  setElements(document, meta, el)

  /* Check if using mouse */

  usingMouse()

  /* Get scrollbar width */

  const getScrollbarWidth = (navToggle = false) => {
    const html = document.documentElement
    const w = window.innerWidth - html.clientWidth

    html.style.setProperty('--scrollbar-width', `${w}px`)
  }

  onResize(() => {
    getScrollbarWidth()
  }, 800)

  getScrollbarWidth()

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
        open: el.navOpen,
        close: el.navClose,
        overlay: el.navOverlay,
        onToggle (open) {
          if (open) {
            document.documentElement.setAttribute('data-100-vw', 'true')
            document.documentElement.setAttribute('data-nav-open', 'true')
          }
        },
        endToggle () {
          document.documentElement.setAttribute('data-100-vw', 'false')
          document.documentElement.setAttribute('data-nav-open', 'false')
        },
        filterFocusableItem (item) {
          return el.logo !== item
        }
      })
    }

    nav()
  }

  /* Search */

  if (el.search && el.searchButton && el.searchInput && el.searchBar) {
    let searchOpen = false

    const toggleSearchBar = (open) => {
      searchOpen = open

      el.searchButton.setAttribute('aria-expanded', searchOpen)

      if (searchOpen) {
        el.searchBar.setAttribute('data-display', true)

        setTimeout(() => {
          el.searchBar.setAttribute('data-open', true)
        }, 50)
      }

      if (!searchOpen) {
        el.searchBar.setAttribute('data-open', false)

        setTimeout(() => {
          el.searchBar.setAttribute('data-display', false)
        }, 300)
      }

      if (searchOpen) {
        setTimeout(() => {
          el.searchInput.focus()
        }, 100)
      }
    }

    el.searchButton.addEventListener('click', () => {
      toggleSearchBar(!searchOpen)
    })

    document.body.addEventListener('keydown', (e) => {
      if (getKey(e) === 'ESC' && searchOpen) {
        toggleSearchBar(false)

        el.searchButton.focus()
      }
    })
  }

  /* Hero - set min height */

  if (el.hero && el.heroTarget) {
    const setMinHeight = () => {
      const h = el.heroTarget.clientHeight

      el.hero.style.setProperty('--min-height', `${h}px`)
    }

    onResize(() => {
      setMinHeight()
    })

    setMinHeight()
  }

  /* Modal triggers and modals */

  if (el.modalTriggers.length) {
    const modal = (args) => {
      return new Modal(args)
    }

    el.modalTriggers.forEach((m) => {
      /* Get elements */

      const meta = [
        {
          prop: 'modal',
          selector: `#${m.getAttribute('aria-controls')}`,
          items: [
            {
              prop: 'window',
              selector: '.o-modal__window'
            },
            {
              prop: 'overlay',
              selector: '.o-modal__overlay'
            },
            {
              prop: 'close',
              selector: '.o-modal__close'
            },
            {
              prop: 'iframe',
              selector: 'iframe'
            }
          ]
        }
      ]

      const args = {}

      setElements(document, meta, args)

      args.trigger = m

      /* Iframe player */

      const { iframe } = args

      let iframeLink = ''
      let player = false

      if (iframe) {
        iframeLink = iframe.getAttribute('data-src')
      }

      args.onToggle = (open) => {
        document.documentElement.setAttribute('data-100-vw', open)

        if (iframeLink && open && !player) {
          iframe.src = `${iframeLink}?autoplay=1&enablejsapi=1`

          /* Load IFrame Player API code */

          const tag = document.createElement('script')
          tag.src = 'https://www.youtube.com/iframe_api'

          const firstScriptTag = document.getElementsByTagName('script')[0]
          firstScriptTag.parentNode.insertBefore(tag, firstScriptTag)

          window.onYouTubeIframeAPIReady = () => {
            player = new window.YT.Player(iframe.id, {
              events: {
                onReady: window.onPlayerReady
              }
            })
          }

          window.onPlayerReady = (event) => {
            iframe.focus()
            event.target.playVideo()
          }
        }

        if (player) {
          if (!open) {
            if (player.getPlayerState() === 1 || player.getPlayerState() === 3) {
              setTimeout(() => {
                player.stopVideo()
              }, 300)
            }
          } else {
            if (player.getPlayerState() !== 1) {
              setTimeout(() => {
                player.playVideo()
              }, 300)
            }
          }
        }
      }

      /* Init */

      modal(args)
    })
  }

  /* Collapsibles */

  if (el.collapsibles.length) {
    const collapsible = (args) => {
      return new Collapsible(args)
    }

    el.collapsibles.forEach(c => {
      const meta = [
        {
          prop: 'collapsible',
          selector: '.o-collapsible__main'
        },
        {
          prop: 'trigger',
          selector: '.o-collapsible__toggle'
        }
      ]

      const cc = {}

      setElements(c, meta, cc)

      const args = {
        container: c,
        collapsible: cc.collapsible,
        accordionId: c.getAttribute('data-accordion'),
        trigger: cc.trigger
      }

      collapsible(args)
    })
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
              <svg width="25" height="25" viewBox="0 0 20 20" fill="none" aria-label="Error" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg"><path d="M10.0001 14.1666C10.1945 14.1666 10.3577 14.1007 10.4897 13.9687C10.6216 13.8368 10.6876 13.6736 10.6876 13.4791C10.6876 13.2847 10.6216 13.1215 10.4897 12.9895C10.3577 12.8576 10.1945 12.7916 10.0001 12.7916C9.80564 12.7916 9.64244 12.8576 9.5105 12.9895C9.37855 13.1215 9.31258 13.2847 9.31258 13.4791C9.31258 13.6736 9.37855 13.8368 9.5105 13.9687C9.64244 14.1007 9.80564 14.1666 10.0001 14.1666ZM10.0001 18.3333C8.86119 18.3333 7.7848 18.1145 6.77091 17.677C5.75703 17.2395 4.87161 16.6423 4.11466 15.8854C3.35772 15.1284 2.7605 14.243 2.323 13.2291C1.8855 12.2152 1.66675 11.1319 1.66675 9.97913C1.66675 8.84024 1.8855 7.76385 2.323 6.74996C2.7605 5.73607 3.35772 4.85413 4.11466 4.10413C4.87161 3.35413 5.75703 2.76038 6.77091 2.32288C7.7848 1.88538 8.86814 1.66663 10.0209 1.66663C11.1598 1.66663 12.2362 1.88538 13.2501 2.32288C14.264 2.76038 15.1459 3.35413 15.8959 4.10413C16.6459 4.85413 17.2397 5.73607 17.6772 6.74996C18.1147 7.76385 18.3334 8.84718 18.3334 9.99996C18.3334 11.1388 18.1147 12.2152 17.6772 13.2291C17.2397 14.243 16.6459 15.1284 15.8959 15.8854C15.1459 16.6423 14.264 17.2395 13.2501 17.677C12.2362 18.1145 11.1529 18.3333 10.0001 18.3333ZM10.0626 10.9791C10.2431 10.9791 10.3924 10.9201 10.5105 10.802C10.6286 10.684 10.6876 10.5347 10.6876 10.3541V6.33329C10.6876 6.15274 10.6286 6.00343 10.5105 5.88538C10.3924 5.76732 10.2431 5.70829 10.0626 5.70829C9.88203 5.70829 9.73272 5.76732 9.61467 5.88538C9.49661 6.00343 9.43758 6.15274 9.43758 6.33329V10.3541C9.43758 10.5347 9.49661 10.684 9.61467 10.802C9.73272 10.9201 9.88203 10.9791 10.0626 10.9791Z" fill="currentColor"/></svg>
            </span>
            <span class='t-line-height-0'>
              <span class='t-h6 t-line-height-150-pc' id='%id-text'>%message</span>
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

  /* Conditional inputs */

  if (el.conditionals.length) {
    const conditional = (item) => {
      return new Conditional(item)
    }

    el.conditionals.forEach((c) => {
      conditional(c)
    })
  }

  /* Fieldsets - equalize labels if radio-select or radio-text */

  if (el.fieldsets.length) {
    el.fieldsets.forEach((fieldset) => {
      /* Get elements */

      const meta = [
        {
          prop: 'radioSelect',
          selector: '[data-type="radio-select"]'
        },
        {
          prop: 'radioText',
          selector: '[data-type="radio-text"]'
        },
        {
          prop: 'labels',
          selector: '[data-type|="radio"] [data-label]',
          all: true,
          array: true
        }
      ]

      const f = {}

      setElements(fieldset, meta, f)

      /* Get and set max width */

      const setWidth = () => {
        if (f.radioSelect || f.radioText) {
          fieldset.style.setProperty('--label-width', 'auto')

          const width = Math.max.apply(null, f.labels.map((l) => l.clientWidth))

          fieldset.style.setProperty('--label-width', `${width / 16}rem`)
        }
      }

      setWidth()

      onResize(setWidth())
    })
  }

  /* Slider */

  if (el.slider.length) {
    const slider = (args) => {
      return new Slider(args)
    }

    el.slider.forEach(s => {
      const meta = [
        {
          prop: 'main',
          selector: '.o-slider__main'
        },
        {
          prop: 'track',
          selector: '.o-slider__track'
        },
        {
          prop: 'panels',
          selector: '[role="tabpanel"]',
          all: true
        },
        {
          prop: 'items',
          selector: '.o-slider__inner',
          all: true
        },
        {
          prop: 'nav',
          selector: '[role="tab"]',
          all: true
        },
        {
          prop: 'prev',
          selector: '[data-prev]'
        },
        {
          prop: 'next',
          selector: '[data-next]'
        }
      ]

      const ss = {}

      setElements(s, meta, ss)

      const args = {
        tabs: ss.nav,
        panels: ss.panels,
        container: s,
        slider: ss.main,
        track: ss.track,
        targetHeight: ss.track,
        prev: ss.prev,
        next: ss.next,
        duration: 500,
        reduceMotion
      }

      const loop = ss.main.getAttribute('data-loop') === 'true'
      const type = s.getAttribute('data-type')

      if (loop) {
        args.loop = true
      }

      if (type === 'group') {
        args.groupItems = ss.items
        args.groupSelector = '.o-slider__insert'
        args.breakpoints = [
          {
            breakpoint: 0,
            items: parseInt(s.getAttribute('data-0'))
          },
          {
            breakpoint: 600 * multiplier,
            items: parseInt(s.getAttribute('data-600'))
          },
          {
            breakpoint: 900 * multiplier,
            items: parseInt(s.getAttribute('data-900'))
          },
          {
            breakpoint: 1200 * multiplier,
            items: parseInt(s.getAttribute('data-1200'))
          }
        ]
      }

      slider(args)
    })
  }
}

initialize()

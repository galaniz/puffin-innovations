/**
 * Theme js
 */

/* Imports */

import { usingMouse } from 'Formation/utils'

/* Init */

const initialize = () => {
  const ns = 'pi'

  /* Check if reduce motion */

  let reduceMotion = false
  const mediaQuery = window.matchMedia('(prefers-reduced-motion: reduce)')

  if (!mediaQuery || mediaQuery.matches) { reduceMotion = true }

  /* Check if using mouse */

  usingMouse()

  console.log('PI', ns, reduceMotion)
} // End initialize

initialize()

/**
 * Container block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj,
  getColorSlug
} = window.blockUtils

const {
  Panel,
  PanelBody,
  TextControl,
  SelectControl,
  ColorPalette,
  BaseControl,
  CheckboxControl,
  RadioControl
} = window.wp.components

const {
  InspectorControls,
  InnerBlocks
} = window.wp.blockEditor

const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'container'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Convert hex to rgb */

const alphaCodes = {
  FF: 1,
  FC: 0.99,
  FA: 0.98,
  F7: 0.97,
  F5: 0.96,
  F2: 0.95,
  F0: 0.94,
  ED: 0.93,
  EB: 0.92,
  E8: 0.91,
  E6: 0.9,
  E3: 0.89,
  E0: 0.88,
  DE: 0.87,
  DB: 0.86,
  D9: 0.85,
  D6: 0.84,
  D4: 0.83,
  D1: 0.82,
  CF: 0.81,
  CC: 0.8,
  C9: 0.79,
  C7: 0.78,
  C4: 0.77,
  C2: 0.76,
  BF: 0.75,
  BD: 0.74,
  BA: 0.73,
  B8: 0.72,
  B5: 0.71,
  B3: 0.7,
  B0: 0.69,
  AD: 0.68,
  AB: 0.67,
  A8: 0.66,
  A6: 0.65,
  A3: 0.64,
  A1: 0.63,
  '9E': 0.62,
  '9C': 0.61,
  99: 0.6,
  96: 0.59,
  94: 0.58,
  91: 0.57,
  '8F': 0.56,
  '8C': 0.55,
  '8A': 0.54,
  87: 0.53,
  85: 0.52,
  82: 0.51,
  80: 0.5,
  '7D': 0.49,
  '7A': 0.48,
  78: 0.47,
  75: 0.46,
  73: 0.45,
  70: 0.44,
  '6E': 0.43,
  '6B': 0.42,
  69: 0.41,
  66: 0.4,
  63: 0.39,
  61: 0.38,
  '5E': 0.37,
  '5C': 0.36,
  59: 0.35,
  57: 0.34,
  54: 0.33,
  52: 0.32,
  '4F': 0.31,
  '4D': 0.3,
  '4A': 0.29,
  47: 0.28,
  45: 0.27,
  42: 0.26,
  40: 0.25,
  '3D': 0.24,
  '3B': 0.23,
  38: 0.22,
  36: 0.21,
  33: 0.2,
  30: 0.19,
  '2E': 0.18,
  '2B': 0.17,
  29: 0.16,
  26: 0.15,
  24: 0.14,
  21: 0.13,
  '1F': 0.12,
  '1C': 0.11,
  '1A': 0.1,
  17: 0.09,
  14: 0.08,
  12: 0.07,
  '0F': 0.06,
  '0D': 0.05,
  '0A': 0.04,
  '08': 0.03,
  '05': 0.02,
  '03': 0.01,
  '00': 0
}

const hexToRgba = (h = '') => {
  let r = 0
  let g = 0
  let b = 0
  let a = 1

  r = `0x${h[1]}${h[2]}`
  g = `0x${h[3]}${h[4]}`
  b = `0x${h[5]}${h[6]}`

  if (h.length === 9) {
    a = alphaCodes[(h[7] + h[8]).toUpperCase()]
  }

  r = +r
  g = +g
  b = +b

  return [r, g, b, a].join(', ')
}

/* Block */

registerBlockType(name, {
  title: 'Container',
  category: 'theme-blocks',
  icon: 'grid-view',
  attributes: attr,
  edit (props) {
    const { attributes, setAttributes } = props

    const {
      internal_name = def.internal_name, // eslint-disable-line camelcase
      tag = def.tag,
      layout = def.layout,
      wrap = def.wrap,
      contain = def.contain, // eslint-disable-line camelcase
      align = def.align,
      justify = def.justify,
      gap_mobile = def.gap_mobile, // eslint-disable-line camelcase
      gap = def.gap,
      padding_top_mobile = def.padding_top_mobile, // eslint-disable-line camelcase
      padding_top = def.padding_top, // eslint-disable-line camelcase
      padding_bottom_mobile = def.padding_bottom_mobile, // eslint-disable-line camelcase
      padding_bottom = def.padding_bottom, // eslint-disable-line camelcase
      bg_color = def.bg_color, // eslint-disable-line camelcase
      bg_seamless = def.bg_seamless, // eslint-disable-line camelcase
      quote_mark = def.quote_mark, // eslint-disable-line camelcase
      editor_styles = def.editor_styles, // eslint-disable-line camelcase
      order_first = def.order_first // eslint-disable-line camelcase
    } = attributes

    /* Internal name */

    const internalName = internal_name // eslint-disable-line camelcase

    /* Layout */

    const isFlex = layout === 'row' || layout === 'column'

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Container Options'>
            <TextControl
              label='Internal Name'
              help='For editor organization'
              value={internal_name} // eslint-disable-line camelcase
              onChange={v => setAttributes({ internal_name: v })}
            />
            <RadioControl
              label='Layout'
              selected={layout}
              options={[
                { label: 'Block', value: 'block' },
                { label: 'Column', value: 'column' },
                { label: 'Row', value: 'row' }
              ]}
              onChange={layout => setAttributes({ layout })}
            />
            {!isFlex && (
              <Fragment>
                <BaseControl label='Background Color'>
                  <ColorPalette
                    colors={nO.color_options}
                    value={bg_color} // eslint-disable-line camelcase
                    clearable
                    enableAlpha
                    onChange={bg_color => { // eslint-disable-line camelcase
                      const slug = getColorSlug(nO.color_options, bg_color)
                      const custom = bg_color.length > 7 // eslint-disable-line camelcase

                      setAttributes({
                        bg_color, // eslint-disable-line camelcase
                        bg_color_slug: slug,
                        bg_color_custom: custom ? `rgba(${hexToRgba(bg_color)})` : '' // eslint-disable-line camelcase
                      })
                    }}
                  />
                </BaseControl>
                <CheckboxControl
                  label='Background seamless'
                  value='1'
                  checked={!!bg_seamless} // eslint-disable-line camelcase
                  onChange={checked => setAttributes({ bg_seamless: checked })}
                />
              </Fragment>
            )}
            <SelectControl
              label='Tag'
              value={tag} // eslint-disable-line camelcase
              options={nO.html_options}
              onChange={tag => setAttributes({ tag })}
            />
            {!isFlex && (
              <Fragment>
                <SelectControl
                  label='Padding Top'
                  value={padding_top_mobile} // eslint-disable-line camelcase
                  options={nO.padding_options}
                  onChange={v => setAttributes({ padding_top_mobile: v })}
                />
                <SelectControl
                  label='Padding Top Larger Screens'
                  value={padding_top} // eslint-disable-line camelcase
                  options={nO.padding_options}
                  onChange={v => setAttributes({ padding_top: v })}
                />
                <SelectControl
                  label='Padding Bottom'
                  value={padding_bottom_mobile} // eslint-disable-line camelcase
                  options={nO.padding_options}
                  onChange={v => setAttributes({ padding_bottom_mobile: v })}
                />
                <SelectControl
                  label='Padding Bottom Larger Screens'
                  value={padding_bottom} // eslint-disable-line camelcase
                  options={nO.padding_options}
                  onChange={v => setAttributes({ padding_bottom: v })}
                />
              </Fragment>
            )}
            {isFlex && (
              <Fragment>
                <SelectControl
                  label='Gap'
                  value={gap_mobile} // eslint-disable-line camelcase
                  options={nO.gap_options}
                  onChange={v => setAttributes({ gap_mobile: v })}
                />
                <SelectControl
                  label='Gap Larger Screens'
                  value={gap} // eslint-disable-line camelcase
                  options={nO.gap_options}
                  onChange={v => setAttributes({ gap: v })}
                />
                <SelectControl
                  label='Justify'
                  value={justify}
                  options={[
                    { label: 'None', value: '' },
                    { label: 'Start', value: 'start' },
                    { label: 'Center', value: 'center' },
                    { label: 'End', value: 'end' },
                    { label: 'Space Between', value: 'between' }
                  ]}
                  onChange={justify => setAttributes({ justify })}
                />
                <SelectControl
                  label='Align'
                  value={align}
                  options={[
                    { label: 'None', value: '' },
                    { label: 'Start', value: 'start' },
                    { label: 'Center', value: 'center' },
                    { label: 'End', value: 'end' }
                  ]}
                  onChange={align => setAttributes({ align })}
                />
              </Fragment>
            )}
            {layout === 'row' && (
              <CheckboxControl
                label='Wrap'
                help='Items wrap onto multiple rows'
                value='1'
                checked={!!wrap}
                onChange={wrap => setAttributes({ wrap })}
              />
            )}
            {!isFlex && (
              <CheckboxControl
                label='Contain'
                help='Limit to 1300px container'
                value='1'
                checked={!!contain} // eslint-disable-line camelcase
                onChange={contain => setAttributes({ contain })}
              />
            )}
            {tag === 'blockquote' && (
              <CheckboxControl
                label='Quote Mark'
                help='Display quotation mark above content'
                value='1'
                checked={!!quote_mark} // eslint-disable-line camelcase
                onChange={v => setAttributes({ quote_mark: v })}
              />
            )}
            <CheckboxControl
              label='Editor Styles'
              help='Styles for inline links and lists'
              value='1'
              checked={!!editor_styles} // eslint-disable-line camelcase
              onChange={v => setAttributes({ editor_styles: v })}
            />
            <CheckboxControl
              label='Order First'
              help='Visually appear as first item'
              value='1'
              checked={!!order_first} // eslint-disable-line camelcase
              onChange={v => setAttributes({ order_first: v })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title={`Container${internalName ? `: ${internalName}` : ''}`} initialOpen={false}>
          <InnerBlocks />
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})

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
  SelectControl,
  ColorPalette,
  BaseControl,
  CheckboxControl
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

/* Block */

registerBlockType(name, {
  title: 'Container',
  category: 'theme-blocks',
  icon: 'grid-view',
  attributes: attr,
  edit (props) {
    const { attributes, setAttributes } = props

    const {
      outer_tag = def.outer_tag, // eslint-disable-line camelcase
      tag = def.tag,
      column = def.column,
      wrap = def.wrap,
      fill_width = def.fill_width, // eslint-disable-line camelcase
      align = def.align,
      justify = def.justify,
      gap_mobile = def.gap_mobile, // eslint-disable-line camelcase
      gap = def.gap,
      padding_top_mobile = def.padding_top_mobile, // eslint-disable-line camelcase
      padding_top = def.padding_top, // eslint-disable-line camelcase
      padding_bottom_mobile = def.padding_bottom_mobile, // eslint-disable-line camelcase
      padding_bottom = def.padding_bottom, // eslint-disable-line camelcase
      bg_color = def.bg_color // eslint-disable-line camelcase
    } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Container Options'>
            <SelectControl
              label='Outer Tag'
              value={outer_tag} // eslint-disable-line camelcase
              options={[
                { label: 'Section', value: 'section' },
                { label: 'Div', value: 'div' },
                { label: 'Article', value: 'article' },
                { label: 'Aside', value: 'aside' }
              ]}
              onChange={v => setAttributes({ outer_tag: v })}
            />
            <SelectControl
              label='Tag'
              value={tag}
              options={[
                { label: 'Div', value: 'div' },
                { label: 'Unordered List', value: 'ul' },
                { label: 'Ordered List', value: 'ol' }
              ]}
              onChange={tag => setAttributes({ tag })}
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
            <CheckboxControl
              label='Column'
              help='Otherwise row layout'
              value='1'
              checked={!!column}
              onChange={column => setAttributes({ column })}
            />
            <CheckboxControl
              label='Wrap'
              value='1'
              checked={!!wrap}
              onChange={wrap => setAttributes({ wrap })}
            />
            <CheckboxControl
              label='Fill Width'
              value='1'
              checked={!!fill_width} // eslint-disable-line camelcase
              onChange={v => setAttributes({ fill_width: v })}
            />
            <BaseControl label='Background Color'>
              <ColorPalette
                colors={nO.color_options}
                value={bg_color} // eslint-disable-line camelcase
                clearable={false}
                disableCustomColors
                disableAlpha
                onChange={bg_color => { // eslint-disable-line camelcase
                  const slug = getColorSlug(nO.color_options, bg_color)

                  setAttributes({
                    bg_color, // eslint-disable-line camelcase
                    bg_color_slug: slug
                  })
                }}
              />
            </BaseControl>
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Container'>
          <InnerBlocks />
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})

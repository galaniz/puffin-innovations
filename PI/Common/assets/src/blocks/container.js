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
      bg_seamless = def.bg_seamless // eslint-disable-line camelcase
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

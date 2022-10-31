/**
 * Column block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  TextControl,
  SelectControl,
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
const name = n + 'column'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Block */

registerBlockType(name, {
  title: 'Column',
  category: 'theme-blocks',
  icon: 'columns',
  attributes: attr,
  edit (props) {
    const { attributes, setAttributes } = props

    const {
      internal_name = def.internal_name, // eslint-disable-line camelcase
      tag = def.tag,
      width_immediate = def.width_immediate, // eslint-disable-line camelcase
      width_mobile = def.width_mobile, // eslint-disable-line camelcase
      width = def.width,
      align = def.align,
      justify = def.justify,
      grow = def.grow,
      quote_mark = def.quote_mark, // eslint-disable-line camelcase
      editor_styles = def.editor_styles, // eslint-disable-line camelcase
      order_first = def.order_first // eslint-disable-line camelcase
    } = attributes

    /* Internal name */

    const internalName = internal_name // eslint-disable-line camelcase

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Column Options'>
            <TextControl
              label='Internal Name'
              help='For editor organization'
              value={internal_name} // eslint-disable-line camelcase
              onChange={v => setAttributes({ internal_name: v })}
            />
            <SelectControl
              label='Tag'
              value={tag}
              options={nO.html_options}
              onChange={tag => setAttributes({ tag })}
            />
            <SelectControl
              label='Width'
              value={width_mobile} // eslint-disable-line camelcase
              options={nO.width_options}
              onChange={v => setAttributes({ width_mobile: v })}
            />
            <SelectControl
              label='Width Larger Screens'
              value={width}
              options={nO.width_options}
              onChange={width => setAttributes({ width })}
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
            <CheckboxControl
              label='Grow'
              help='Fill in remaining space'
              value='1'
              checked={!!grow}
              onChange={grow => setAttributes({ grow })}
            />
            <CheckboxControl
              label='Width Immediate'
              help='Specified width applies immediately otherwise applies at small breakpoint'
              value='1'
              checked={!!width_immediate} // eslint-disable-line camelcase
              onChange={v => setAttributes({ width_immediate: v })}
            />
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
        <PanelBody title={`Column${internalName ? `: ${internalName}` : ''}`} initialOpen={false}>
          <InnerBlocks />
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})

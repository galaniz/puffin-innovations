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
      tag = def.tag,
      width_mobile = def.width_mobile, // eslint-disable-line camelcase
      width = def.width,
      justify = def.justify,
      grow = def.grow
    } = attributes

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Column Options'>
            <SelectControl
              label='Tag'
              value={tag}
              options={[
                { label: 'Div', value: 'div' },
                { label: 'List Item', value: 'li' }
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
            <CheckboxControl
              label='Grow'
              value='1'
              checked={!!grow}
              onChange={grow => setAttributes({ grow })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Column'>
          <InnerBlocks />
        </PanelBody>
      </Panel>
    ]
  },
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})

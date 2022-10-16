/**
 * Image block
 */

/* Dependencies */

const {
  getNamespace,
  getNamespaceObj
} = window.blockUtils

const {
  Panel,
  PanelBody,
  SelectControl
} = window.wp.components

const {
  InspectorControls,
  InnerBlocks
} = window.wp.blockEditor

const { withSelect } = window.wp.data
const { Fragment } = window.wp.element
const { registerBlockType } = window.wp.blocks

/* Namespace */

const n = getNamespace(true)
const name = n + 'image'

/* Attributes from serverside */

const nO = getNamespaceObj(getNamespace())
const attr = nO.blocks[name].attr
const def = nO.blocks[name].default

/* Allowed blocks */

const allowedBlocks = ['core/image']

/* Limit innerblocks */

const dataSelector = withSelect((select, ownProps) => {
  const blocks = select('core/block-editor').getBlocks(ownProps.clientId)
  const args = { id: 0 }

  blocks.forEach(b => {
    if (b.name === 'core/image') {
      const { id = 0 } = b.attributes
      args.id = id
    }
  })

  if (args.id) { ownProps.attributes.id = args.id }

  args.isMaxBlock = blocks.length === 1

  return args
})

/* Block */

registerBlockType(name, {
  title: 'Image',
  category: 'theme-blocks',
  icon: 'camera-alt',
  attributes: attr,
  edit: dataSelector(props => {
    const { attributes, setAttributes, isMaxBlock } = props

    const {
      width_mobile = def.width_mobile, // eslint-disable-line camelcase
      width = def.width,
      aspect_ratio = def.aspect_ratio, // eslint-disable-line camelcase
      border_radius = def.border_radius // eslint-disable-line camelcase
    } = attributes

    const widthOptions = [
      { label: 'None', value: '' },
      { label: '40px', value: 's' },
      { label: '60px', value: 'm' },
      { label: '80px', value: 'l' },
      { label: '100px', value: 'xl' },
      { label: '200px', value: '4xl' },
      { label: '100%', value: '1-1' }
    ]

    /* Output */

    return [
      <Fragment key='frag'>
        <InspectorControls>
          <PanelBody title='Image Options'>
            <SelectControl
              label='Width'
              value={width_mobile} // eslint-disable-line camelcase
              options={widthOptions}
              onChange={v => setAttributes({ width_mobile: v })}
            />
            <SelectControl
              label='Width Larger Screens'
              value={width}
              options={widthOptions}
              onChange={width => setAttributes({ width })}
            />
            <SelectControl
              label='Aspect Ratio'
              value={aspect_ratio} // eslint-disable-line camelcase
              options={[
                { label: 'None', value: '' },
                { label: '1:1', value: '100' },
                { label: '3:2', value: '66' },
                { label: '16:9', value: '56' }
              ]}
              onChange={v => setAttributes({ aspect_ratio: v })}
            />
            <SelectControl
              label='Border Radius'
              value={border_radius} // eslint-disable-line camelcase
              options={[
                { label: 'None', value: '' },
                { label: '100%', value: '100-pc' },
                { label: '15px', value: 'm' },
                { label: '30px', value: 'xl' }
              ]}
              onChange={v => setAttributes({ border_radius: v })}
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>,
      <Panel key='panel'>
        <PanelBody title='Image' initialOpen={false}>
          <InnerBlocks
            templateLock={false}
            allowedBlocks={isMaxBlock ? [] : allowedBlocks}
          />
        </PanelBody>
      </Panel>
    ]
  }),
  save () {
    return <InnerBlocks.Content /> // Rendered in php
  }
})
